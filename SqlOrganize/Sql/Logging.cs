﻿using Microsoft.VisualBasic;
using Newtonsoft.Json;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;

namespace SqlOrganize.Sql
{
    /// <summary>
    /// Registro de errores
    /// </summary>
    public class Logging
    {


        /// <summary>Atributo para registrar errores</summary>
        /// <example>
        /// [
        ///   "asignatura": [  {"level": LEVEL_ERROR, "msg": "No puede estar vacío", type: "required"}  ]
        ///   "plan": [ {"level": LEVEL_WARNING, "msg: "No tiene cargas horarias asociadas", type: "user"}  ]
        ///   "numero": [
        ///       { "level": LEVEL_ERROR, "msg": "No es unico", type: "not_unique"}
        ///       { "level": LEVEL_WARNING, "msg": "Esta fuera del rango permitido", type: "out_of_range"}
        ///   ]
        /// ]
        /// </example>
        public Dictionary<string, List<(Level level, string msg, string? type)>> logs { get; } = new ();

        public enum Level
        {
            Success,
            Info,
            Warning,
            Error,
        }

        /// <summary>Logs de una determinada llave</summary>
        public List<(Level level, string msg, string? type)>? LogsByKey(string key)
        {
            return logs.ContainsKey(key) ? logs[key] : null;
        }

        /// <summary>Vaciar logs de una determinada llave y reasignar level</summary>
        public void ClearByKey(string key)
        {
            if (logs.ContainsKey(key))
            {
                logs.Remove(key);
            }
        }

        public void Clear()
        {
            logs.Clear();
        }
            
        public void AddLog(string key, string msg, string type = null, Level level = 0)
        {
            if (!logs.ContainsKey(key))
                logs[key] = new List<(Level level, string msg, string? type)> { };

            logs[key].Add((level, msg, type));
            logs[key].Sort((x, y) => {
                return x.level.CompareTo(y.level);
            });
        }

        public void AddLogging(Logging logging)
        {
            foreach(var (key, value) in logging.logs)
            {
                foreach(var info in value)
                    AddLog(key, info.msg, info.type, info.level);
            }
        }

        public void AddErrorLog(string key, string msg, string type = null)
        {
            AddLog(key, msg, type, Level.Error);
        }

        public Level? LevelFromKey(string key)
        {
            if (!logs.ContainsKey(key))
                return null;

            Level level = Level.Info;
            foreach (var log in logs[key])
            {
                if (log.level > level)
                {
                    level = log.level;
                }
            }

            return level;
        }

        public bool HasLogs()
        {
            return !logs.IsNoE();
        }

        public bool HasErrors()
        {
            foreach(var (key, logsEntity) in logs)
                foreach(var log in logsEntity)
                    if (log.level == Level.Error) return true;

            return false;
        }

        public Dictionary<string, List<(Level level, string msg, string? type)>> LogsByLevel(Level level)
        {
            Dictionary<string, List<(Level level, string msg, string? type)>> response = new();

            foreach (var (key, logskey) in logs)
            {
                List < (Level level, string msg, string? type)> logsResponse = new();

                foreach (var log in logskey)
                    if (log.level == Level.Error)
                        logsResponse.Add(log);

                if (logsResponse.Count > 0)
                    response[key] = logsResponse;
            }
            return new();
        }

        public override string ToString() {
            List<string> r = new();
            foreach (var (key, log) in logs)
            {
                foreach (var l in log)
                {
                    r.Add(key + ": "+l.msg);
                }

            }
            return JsonConvert.SerializeObject(r, Formatting.Indented);
        }


        public void Merge(Logging logging)
        {
            foreach (var (key, logs) in logging.logs)
            {
                if (!this.logs.ContainsKey(key))
                    this.logs[key] = new();

                foreach (var log in logs)
                    this.logs[key].Add(log);
            }
        }

    }
}
