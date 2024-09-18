using SqlOrganize.DateTimeUtils;
using SqlOrganize.ValueTypesUtils;
using System.ComponentModel;
using static System.Runtime.InteropServices.JavaScript.JSType;

namespace SqlOrganize.Sql.Fines2Model3
{
    public class CalificacionValues : EntityVal
    {
        public CalificacionValues(Db _db, string _entity_name, string? _field_id) : base(_db, _entity_name, _field_id)
        {
        }

        public CalificacionValues SetFromPlanilla(string text)
        {
            var values = text.Split("\t"); //5 valores nombres, apellidos, numero_documento, nota_final, crec

            if (values.Count() <= 0)
                throw new Exception("Sin datos para asignar");

            Set("persona__nombres", values[0]);

            if (values.Count() != 5)
                throw new Exception("Cantidad de valores distinto a 5, no será procesado.");

            bool asignarCrec = false;
            try
            {
                Set("nota_final", Convert.ToDecimal(values[3]));
            }
            catch (Exception ex)
            {
                asignarCrec = true;
            }

            if (((decimal)Get("nota_final")) < 7)
                asignarCrec = true;


            if (asignarCrec)
            {
                try
                {
                    Set("crec", Convert.ToDecimal(values[4]));
                }
                catch (Exception ex)
                {
                    throw new Exception("No se puede asignar nota final ni crec.");
                }

                if (((decimal)Get("crec")) < 4)
                    throw new Exception("No se puede asignar nota final ni crec.");
            }

            Set("persona__apellidos", values[1]);
            var cuilDni = PersonaValues.CuilDni(values[2]);

            Sset("persona__numero_documento", cuilDni.dni);
            Sset("persona__cuil", cuilDni.cuil);

            if (GetStr("persona__numero_documento").Length < 7 || GetStr("persona__numero_documento").Length > 8)
                throw new Exception("Longitud de DNI incorrecta.");

            return this;


        }


        /// <summary>
        /// Definir dato en base al procesamiento de una fila de la planilla de calificacion
        /// </summary>
        public CalificacionValues SetFromProgramaFines(string text)
        {
            var values = text.Split("\t");

            if (values.Count() <= 0)
                throw new Exception("Sin datos para asignar");

            Set("persona__nombres", values[0]);

            if (values.Count() != 2)
                throw new Exception("Cantidad de valores distinto a 2, no será procesado.");

            try
            {
                Set("nota_final", Convert.ToDecimal(values[1]));
            } 
            catch (Exception ex)
            {
                throw new Exception("No se puede asignar la nota final " + ex.Message);
            }

            if (((decimal)Get("nota_final")) < 7)
                throw new Exception("El alumno esta desaprobado");

            string[] nombreYDni = values[0].Split("DNI");
            if (nombreYDni.Count() != 2)
                throw new Exception("Procesamiento de nombres y dni incorrecto.");

            string[] nombresApellidos = nombreYDni[0].Split(',');
            if (nombresApellidos.Count() != 2)
                throw new Exception("Procesamiento de nombres y apellidos incorrecto.");

            Set("persona__apellidos", nombresApellidos[0].CleanStringOfDigits()!.Trim());
            Set("persona__nombres", nombresApellidos[1].Trim());
            Set("persona__numero_documento", nombreYDni[1].Trim());

            if (GetStr("persona__numero_documento").Length < 7 || GetStr("persona__numero_documento").Length > 8)
                throw new Exception("Longitud de DNI incorrecta.");

            return this;
        }

        public EntityPersist PersistProcesarCurso(object idCurso)
        {
            Set("curso", idCurso);

            logging.Clear();

            if (IsNullOrEmpty("curso")) throw new Exception("No está definido el curso");
            var cursoObj = db.Sql("curso").Equal("id", Get("curso")).Cache().Dict()!.Obj<Curso>();

            if (cursoObj.disposicion.IsNoE())
                throw new Exception("Disposicion no definida");

            Set("disposicion", cursoObj.disposicion);

            PersonaValues personaVal = (PersonaValues)GetValues("persona");
            AlumnoComisionValues asignacionValues = (AlumnoComisionValues)db.Values("alumno_comision");
            EntityPersist persist = asignacionValues.
                PersistProcesarComisionPersona(cursoObj.comision!, personaVal);

            if (asignacionValues.Logging.HasLogs())
                Logging.AddLogging(asignacionValues.Logging);
           
            if (asignacionValues.IsNullOrEmpty("alumno")) throw new Exception("Alumno no definido");
            Set("alumno", asignacionValues.Get("alumno"));

            var calificacionData = db.CalificacionDisposicionAlumnosSql(cursoObj.disposicion!, Get("alumno")).Cache().Dict();

            if (calificacionData.IsNoE())
            {
                Default().Reset().Insert(persist);
            }
            else
            {
                decimal notaFinalExistent;

                try
                {
                    notaFinalExistent = Convert.ToDecimal(calificacionData!["nota_final"]);
                } catch (Exception ex)
                {
                    notaFinalExistent = -1;
                }
                 
                if (notaFinalExistent < 7)
                {
                    logging.AddLog("calificacion", "Existe calificacion desaprobada, se actualizara", "update", Logging.Level.Warning);
                    Set("id", calificacionData["id"]);
                    persist.UpdateValueIds("calificacion", "nota_final", Get("nota_final"), calificacionData["id"]);
                } else
                {
                    SetValues(calificacionData!);
                    logging.AddLog("calificacion", "Ya existe calificacion aprobada con " + notaFinalExistent, null, Logging.Level.Warning);
                }
            }

            if (IsNullOrEmpty("id"))
                throw new Exception("id de calificacion no definida");

            

            return persist;
        }


        /// <summary>Crear y devolver calificacion error</summary>
        /// <remarks>Este metodo quiza deberia estar en un item y ser un constructor estatico?</remarks>
        public Calificacion GetCalificacionConError(int id, string message, string nombres)
        {
            var calificacionError = db.Data<Calificacion>();
            calificacionError.IsError = true;
            calificacionError.Msg += "Id " + id + ": " + message;
            calificacionError.alumno_.persona_.nombres = nombres;
            return calificacionError;
        }


        public static string GetNotaAprobada(decimal? nota_final, decimal? crec)
        {
            if (nota_final.IsNoE() || nota_final < 7)
                return (crec.IsNoE() || crec < 4) ? "" : Convert.ToInt16(crec).ToString() + "c";

            return Convert.ToInt16(nota_final)!.ToString()!;
        }
    }
}
