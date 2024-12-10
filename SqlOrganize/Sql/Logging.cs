using Microsoft.VisualBasic;
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
        public enum Level
        {
            Success,
            Info,
            Warning,
            Error,
        }

        /// <summary> Dictionary to store logs grouped by key. </summary>
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
        public Dictionary<string, List<(Level Level, string Message, string? Type)>> Logs { get; } = new ();


        /// <summary> Get logs for a specific key. </summary>
        public List<(Level Level, string Message, string? Type)>? GetLogsByKey(string key) =>
            Logs.TryGetValue(key, out var logs) ? logs : null;

        /// <summary>  Clears logs for a specific key. </summary>
        public void ClearByKey(string key) => Logs.Remove(key);

        /// <summary> Clears all logs. </summary>
        public void Clear() => Logs.Clear();

        /// <summary> Adds a new log entry. </summary>
        public void AddLog(string key, string message, string? type = null, Level level = Level.Info)
        {
            if (!Logs.ContainsKey(key))
                Logs[key] = new();

            Logs[key].Add((level, message, type));
            // Sorting logs by level
            Logs[key] = Logs[key].OrderBy(log => log.Level).ToList();
        }

        /// <summary>  Merges logs from another Logging instance. </summary>
        public void Merge(Logging other)
        {
            foreach (var (key, otherLogs) in other.Logs)
            {
                if (!Logs.ContainsKey(key))
                    Logs[key] = new();

                Logs[key].AddRange(otherLogs);
                Logs[key] = Logs[key].OrderBy(log => log.Level).ToList();
            }
        }

        /// <summary> Adds an error log entry. </summary>
        public void AddErrorLog(string key, string message, string? type = null) =>
            AddLog(key, message, type, Level.Error);

        /// <summary> Retrieves the highest log level for a specific key. </summary>
        public Level? GetHighestLevelForKey(string key) =>
            Logs.TryGetValue(key, out var logs) ? logs.Max(log => log.Level) : null;

        /// <summary> Checks if any logs exist. </summary>
        public bool HasLogs() => Logs.Any();

        /// <summary>  Checks if any error logs exist. </summary>
        public bool HasErrors() =>
            Logs.Values.Any(logList => logList.Any(log => log.Level == Level.Error));

        /// <summary> Retrieves logs filtered by a specific level. </summary>
        public Dictionary<string, List<(Level Level, string Message, string? Type)>> GetLogsByLevel(Level level) =>
            Logs
                .Where(entry => entry.Value.Any(log => log.Level == level))
                .ToDictionary(
                    entry => entry.Key,
                    entry => entry.Value.Where(log => log.Level == level).ToList()
                );


        /// <summary> Converts logs to a JSON string representation. </summary>
        public override string ToString() =>
            JsonConvert.SerializeObject(
                Logs.SelectMany(entry => entry.Value.Select(log => new
                {
                    Key = entry.Key,
                    log.Message
                })),
                Formatting.Indented
            );

    }
}
