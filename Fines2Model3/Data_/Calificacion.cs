using SqlOrganize;
using SqlOrganize.CollectionUtils;
using SqlOrganize.Sql;
using SqlOrganize.Sql.Fines2Model3;
using SqlOrganize.ValueTypesUtils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SqlOrganize.Sql.Fines2Model3
{
    public partial class Calificacion
    {
        public string nota_aprobada { 
            get {
                if (nota_final.IsNoE() || nota_final < 7)
                    return (crec.IsNoE() || crec < 4) ? "" : crec + "c";
                
                return nota_final.ToString()!;
            }    
        
        }

        /// <summary>
        /// Definir dato en base al procesamiento de una fila de la planilla de calificacion
        /// </summary>
        public void SetFromProgramaFines(string text)
        {
            var values = text.Split("\t");

            if (values.Count() <= 0)
                throw new Exception("Sin datos para asignar");

            alumno_ = new();
            alumno_.persona_ = new();
            alumno_.persona_.nombres = values[0];

            if (values.Count() != 2)
                throw new Exception("Cantidad de valores distinto a 2, no será procesado.");

            try
            {
                nota_final = Convert.ToDecimal(values[1]);
            }
            catch (Exception ex)
            {
                throw new Exception("No se puede asignar la nota final " + ex.Message);
            }

            if (nota_final < 7)
                throw new Exception("El alumno esta desaprobado");

            string[] nombreYDni = values[0].Split("DNI");
            if (nombreYDni.Count() != 2)
                throw new Exception("Procesamiento de nombres y dni incorrecto.");

            string[] nombresApellidos = nombreYDni[0].Split(',');
            if (nombresApellidos.Count() != 2)
                throw new Exception("Procesamiento de nombres y apellidos incorrecto.");

            alumno_.persona_.apellidos = nombresApellidos[0].CleanStringOfDigits()!.Trim();
            alumno_.persona_.nombres = nombresApellidos[1].Trim();
            alumno_.persona_.numero_documento = nombreYDni[1].Trim();


            if (alumno_.persona_.numero_documento.Length < 7 || alumno_.persona_.numero_documento.Length > 8)
                throw new Exception("Longitud de DNI incorrecta.");
        }

        public void SetFromPlanilla(string text)
        {
            var values = text.Split("\t"); //5 valores nombres, apellidos, numero_documento, nota_final, crec

            if (values.Count() <= 0)
                throw new Exception("Sin datos para asignar");

            alumno_ = new();
            alumno_.persona_ = new();
            alumno_.persona_.nombres = values[0];

            if (values.Count() != 5)
                throw new Exception("Cantidad de valores distinto a 5, no será procesado.");

            bool asignarCrec = false;
            try
            {
                nota_final = Convert.ToDecimal(values[3]);
            }
            catch (Exception ex)
            {
                asignarCrec = true;
            }

            if (nota_final < 7)
                asignarCrec = true;


            if (asignarCrec)
            {
                try
                {
                    crec = Convert.ToDecimal(values[4]);
                }
                catch (Exception ex)
                {
                    throw new Exception("No se puede asignar nota final ni crec.");
                }

                if (crec < 4)
                    throw new Exception("No se puede asignar nota final ni crec.");
            }

            alumno_.persona_.apellidos  = values[1];
            var cuilDni = Persona.CuilDni(values[2]);

            alumno_.persona_.Sset("numero_documento", cuilDni.dni);
            alumno_.persona_.Sset("cuil", cuilDni.cuil);


            if (alumno_.persona_.numero_documento.Length < 7 || alumno_.persona_.numero_documento.Length > 8)
                throw new Exception("Longitud de DNI incorrecta.");
        }

        /// <summary>Crear y devolver calificacion error</summary>
        public static Calificacion CreateCalificacionError(int id, string message, string nombres)
        {
            var calificacionError = new Calificacion();
            calificacionError.Status = "Error";
            calificacionError.Label += "Id " + id + ": " + message;
            calificacionError.alumno_ = new();
            calificacionError.alumno_.persona_ = new();
            calificacionError.alumno_.persona_.nombres = nombres;
            return calificacionError;
        }


        /// <summary> Persistencia de persona, alumno, alumno_comision y calificacion </summary>
        /// <returns> Identificador del objeto persistido </returns>
        /*public object Persist1()
        {
            var persist = db.Persist();

            try
            {

                Reset();

                CompareParams cmp = new()
                {
                    IgnoreNull = true,
                    IgnoreNonExistent = true,
                    FieldsToCompare = new List<string>() { "nombres", "apellidos", "numero_documento" }
                };

                alumno_!.persona = (string)persist.InsertIfNotExistsCompare(alumno_!.persona_!, cmp);
                alumno_.plan = curso_!.comision_!.planificacion_!.plan;
                alumno = (string)persist.InsertIfNotExists(alumno_!);
                
                AlumnoComision alumnoComision = new AlumnoComision();
                alumnoComision.comision_ = curso_!.comision_;
                alumnoComision.alumno_ = alumno_;
                persist.InsertIfNotExists(alumnoComision);

                Calificacion? calificacionExistente = CalificacionDAO.CalificacionDisposicionAlumnosSql(curso_.disposicion!, alumno).Cache().ToEntity<Calificacion>();

                if (calificacionExistente.IsNoE())
                {
                    persist.Insert(this);
                }
                else
                {
                    if (calificacionExistente!.nota_aprobada.IsNoE())
                    {
                        Logging.AddLog("calificacion", "Existe calificacion desaprobada, se actualizara", "update", Logging.Level.Warning);
                        id = calificacionExistente.id;
                        persist.UpdateField(this, "nota_final");
                        persist.UpdateField(this, "crec");
                    }
                    else
                    {
                        Logging.AddLog("calificacion", "Ya existe calificacion aprobada con " + calificacionExistente.nota_aprobada, null, Logging.Level.Warning);
                    }
                }

                Logging.Merge(persist.logging);

                return id!;
            } catch (Exception)
            {
                persist.Clear();
                throw;
            }
        }
        */
    }
}
