using Newtonsoft.Json;
using System.Collections;
using System.Collections.ObjectModel;
using System.Reflection;

namespace SqlOrganize.CollectionUtils
{
    public static class CollectionUtils
    {

        public static IDictionary<string, object?> DictFromText(this string text, params string[] headers)
        {
            string[] values = text.Split("\t");

            if (values.Count() < headers.Count())
                throw new Exception("La cantidad de datos no es suficiente");

            Dictionary<string, object?> dict = new();
            for (var k = 0; k < headers.Length; k++)
                dict[headers[k]] = values[k];

            return dict;
        }

        public static void AddRange<T>(this ObservableCollection<T> oc, IEnumerable<T> items)
        {
            foreach (var item in items)
                oc.Add(item);
        }

        public static void ClearAndAddRange<T>(this ObservableCollection<T> oc, IEnumerable<T> items)
        {
            oc.Clear();
            foreach (var item in items)
                oc.Add(item);
        }

        public static void AddRangeToOC<T>(this IEnumerable<T> data, ObservableCollection<T> oc)
        {
            oc.AddRange(data);
        }

        public static void ClearAndAddRangeToOC<T>(this IEnumerable<T> data, ObservableCollection<T> oc)
        {
            oc.ClearAndAddRange(data);
        }

        /// <summary>
        /// Copiar valores de IDictionary
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="target"></param>
        /// <param name="source"></param>
        /// <remarks>https://stackoverflow.com/questions/8702603/merging-two-objects-in-c-sharp</remarks>
        public static void Copy(this IDictionary<string, object?> target, IDictionary<string, object?> source, bool targetNull = true, bool sourceNotNull = false, bool createKey = false, bool compareNotNull = false, IEnumerable<string>? ignoreKeys = default)
        {
            if(ignoreKeys == default)
                ignoreKeys = new List<string>();

            foreach(var (key, value) in source)
            {
                if (ignoreKeys.Contains(key)) 
                    continue;

                if(!target.ContainsKey(key))
                { 
                    if(!createKey)
                        continue;
                    target[key] = null;
                }

                if (!target[key].IsNoE() && targetNull)
                    continue;

                if (source[key].IsNoE() && sourceNotNull)
                    continue;

                if (compareNotNull && source[key] != null && target[key] != null)
                    if (!source[key]!.ToString()!.Equals(target[key]!.ToString()))
                        throw new Exception("Valores diferentes");

                target[key] = source[key];
            }
        }

        /// <summary>
        /// Copiar valores de objectos
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="target"></param>
        /// <param name="source"></param>
        /// <remarks>https://stackoverflow.com/questions/8702603/merging-two-objects-in-c-sharp</remarks>
        public static void CopyValues<T>(this T target, object source, bool targetNull = true, bool sourceNotNull = false)
        {
            Type t = typeof(T);

            var properties = t.GetProperties().Where(prop => prop.CanRead && prop.CanWrite);

            foreach (var prop in properties)
            {
                var propT = target!.GetType().GetProperty(prop.Name);
                var valorTarget = propT!.GetValue(target, null);

                var valorSource = source.GetPropertyValue(prop.Name);

                if (sourceNotNull && valorSource.IsNoE())
                    continue;

                if (targetNull && !valorTarget.IsNoE())
                    continue;

                prop.SetValue(target, valorSource, null);
            }
        }

        /// <summary>
        /// Copiar valores de tipos diferentes
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <typeparam name="W"></typeparam>
        /// <param name="target"></param>
        /// <param name="source"></param>
        /// <param name="targetNull">La copia se realiza solamente si el campo del target es null</param>
        /// <param name="sourceNotNull">La copia se realiza solamente si el campo del surce es not null</param>
        /// <param name="compareNotNull">Se realiza comparacion de valores no nulos, si son distintos, dispara excepcion</param>
        public static T CopyValues<T, W>(this T target, W source, bool targetNull = true, bool sourceNotNull = false, bool compareNotNull = false)
        {
            Type t = typeof(W);

            var properties = t.GetProperties().Where(prop => prop.CanRead && prop.CanWrite);

            foreach (var prop in properties)
            {
                var propT = target.GetType().GetProperty(prop.Name);
                if (propT == null)
                    continue;

                var valorTarget = propT!.GetValue(target, null);
                var valorSource = prop.GetValue(source, null);

                if (
                    propT.IsNoE() || (
                        targetNull
                        && (!valorTarget.IsNoE())
                    )
                )
                    continue;


                if (compareNotNull && valorSource != null && valorTarget != null)
                    if (!valorSource.ToString()!.Equals(valorTarget.ToString()))
                        throw new Exception("Valores diferentes");

                var value = prop.GetValue(source, null);

                if (sourceNotNull && value.IsNoE())
                    continue;

                propT.SetValue(target, value, null);
            }

            return target;
        }

        public static void Merge(this IDictionary<string, object?> dictionary1, IDictionary<string, object?> dictionary2, string prefix = "")
        {
            dictionary2.ToList().ForEach(pair => dictionary1[prefix + pair.Key] = pair.Value);
        }

        public static void MergeNotNull(this IDictionary<string, object> dictionary1, IDictionary<string, object> dictionary2, string prefix = "")
        {
            dictionary2.ToList().ForEach(pair => {
                if (pair.Value.IsNoE()) { dictionary1[prefix + pair.Key] = pair.Value; } 
            } );
        }

        private static bool IsNumericType(Type type)
        {
            return type == typeof(byte) ||
                   type == typeof(sbyte) ||
                   type == typeof(short) ||
                   type == typeof(ushort) ||
                   type == typeof(int) ||
                   type == typeof(uint) ||
                   type == typeof(long) ||
                   type == typeof(ulong) ||
                   type == typeof(float) ||
                   type == typeof(double) ||
                   type == typeof(decimal);
        }

        public static bool IsList(this object o)
        {
            if (o == null) return false;
            return o is IList &&
                   o.GetType().IsGenericType &&
                   o.GetType().GetGenericTypeDefinition().IsAssignableFrom(typeof(List<>));
        }

        public static bool IsTuple(this object o)
        {
            if (o == null) return false;
            return o is IList &&
                   o.GetType().IsGenericType &&
                   o.GetType().GetGenericTypeDefinition().IsAssignableFrom(typeof(Tuple<>));
        }

        public static bool IsDictionary(this object o)
        {
            if (o == null) return false;
            return o is IDictionary &&
                   o.GetType().IsGenericType &&
                   o.GetType().GetGenericTypeDefinition().IsAssignableFrom(typeof(Dictionary<,>));
        }

        public static bool IsArray(this object o)
        {
            return o.GetType().IsArray;
        }


        /// <summary>
        /// Lista de valores de una entrada del diccionario
        /// </summary>
        /// <typeparam name="T">Tipo de retorno</typeparam>
        /// <param name="rows">Lista de diccionarios</param>
        /// <param name="key">Llave del diccionario</param>
        /// <returns>Lista de valores de una entrada del diccionario</returns>
        public static IEnumerable<string> ColOfValConcat(this IEnumerable<Dictionary<string, object?>> rows, params string[] keys)
        {
            List<string> response = new();
            foreach (Dictionary<string, object?> row in rows)
            {
                List<string> conc = new();

                foreach (var (k, v) in row)
                {
                    if (keys.Contains(k))
                        conc.Add(v!.ToString()!);
                }

                response.Add(string.Join("~", conc));
            }

            return response;
        }


        /// <summary>
        /// Lista de valores de una entrada del diccionario
        /// </summary>
        /// <typeparam name="T">Tipo de retorno</typeparam>
        /// <param name="rows">Lista de diccionarios</param>
        /// <param name="key">Llave del diccionario</param>
        /// <returns>Lista de valores de una entrada del diccionario</returns>
        public static IEnumerable<T> ColOfVal<T>(this IEnumerable<Dictionary<string, object?>> rows, string key)
        {
            List<T> response = new();
            foreach (Dictionary<string, object?> row in rows)
                foreach (var (k, v) in row)
                    if (k == key && v != null)
                        response.Add((T)v);

            return response;
        }

        /// <summary> Enumerable de propiedades </summary>
        /// <typeparam name="T">Tipo de retorno</typeparam>
        /// <typeparam name="V">Tipo del key</typeparam>
        public static IEnumerable<T?> ColOfProp<T, V>(this IEnumerable<V> source, string key)
        {
            Type t = typeof(V);

            var p = t.GetProperty(key);

            List<T?> response = new();

            foreach (var item in source)
                response.Add((T?)p.GetValue(item));

            return response;
        }

        public static IEnumerable<Dictionary<string, object?>> EnumOfDicts(this IEnumerable<object> source)
        {
            List<Dictionary<string, object?>> response = new();

            foreach (var s in source)
                response.Add((Dictionary<string, object?>)s.Dict()!);

            return response!;
        }

        public static void MergeByKeys(this IEnumerable<Dictionary<string, object?>> source, IEnumerable<Dictionary<string, object?>> source2, string key1, string? key2 = null, string prefix = "")
        {
            key2 = key2 ?? key1;

            var s = source2.DictOfDictByKeys(key2);

            foreach (var item in source)
            {
                if (s.ContainsKey(item[key1].ToString()))
                {
                    item.Merge(s[item[key1].ToString()], prefix);
                    continue;
                }
            }
        }

        public static void MergeByKeysLast(this IEnumerable<Dictionary<string, object?>> source, IEnumerable<Dictionary<string, object?>> source2, string key1, string? key2 = null, string prefix = "")
        {
            key2 = key2 ?? key1;

            var s = source2.DictOfDictByKeys(key2);

            foreach (var item in source.Reverse())
            {
                if (s.ContainsKey(item[key1]!.ToString()!))
                {
                    item.Merge(s[item[key1]!.ToString()!], prefix);
                    continue;
                }
            }
        }

        public static IDictionary<string, List<Dictionary<string, object?>>> DictOfListByKeys(this IEnumerable<Dictionary<string, object?>> source, params string[] keys)
        {
            Dictionary<string, List<Dictionary<string, object?>>> response = new();
            foreach (Dictionary<string, object?> row in source)
            {
                List<string> val = new();
                foreach (var k in keys)
                    val.Add(row[k].ToString()!);

                string key = String.Join("~", val.ToArray());

                if (!response.ContainsKey(key))
                    response[key] = new();
                response[key].Add(row);
            }
            return response;
        }

        /// <summary> Diccionario de objetos </summary>
        /// <typeparam name="T">Tipo de propName</typeparam>
        /// <typeparam name="V">Tipo de source</typeparam>
        public static IDictionary<T, List<V>> DictOfListByProperty<T, V>(this IEnumerable<V> source, string propName)
        {
            Dictionary<T, List<V>> response = new();
            foreach (V obj in source)
            {
                var val = (T)obj.GetPropertyValue(propName);
                if (!response.ContainsKey(val))
                    response[val] = new(); 
                response[val].Add(obj);
            }

            return response;
        }


        /// <summary> Diccionario de objetos </summary>
        /// <typeparam name="T">Tipo de propName</typeparam>
        /// <typeparam name="V">Tipo de source</typeparam>
        public static IDictionary<T, V> DictOfObjByProperty<T, V>(this IEnumerable<V> source, string propName)
        {
            Dictionary<T, V> response = new();
            foreach (V obj in source)
            {
                var val = (T)obj.GetPropertyValue(propName);
                response[val] = obj;
            }

            return response;
        }

        /// <summary> Diccionario de objetos pero concatena varios valores </summary>
        /// <typeparam name="T">Tipo de source</typeparam>
        public static IDictionary<string, T> DictOfObjByPropertyNames<T>(this IEnumerable<T> source, params string[] propertyNames)
        {
            Dictionary<string, T> response = new();
            foreach (T obj in source)
            {
                List<string> val = new();
                foreach (string propName in propertyNames)
                {
                    val.Add(obj.GetPropertyValue(propName)!.ToString()!);
                }
                string key = String.Join("~", val.ToArray());
                response[key] = obj;
            }

            return response;
        }

        public static IDictionary<T, Dictionary<string, object?>> DictOfDictByKey<T>(this IEnumerable<Dictionary<string, object?>> source, string key)
        {
            Dictionary<T, Dictionary<string, object?>> response = new();
            foreach (Dictionary<string, object?> row in source)
                response[(T)row[key]!] = row;

            return response;
        }

        public static IDictionary<string, Dictionary<string, object?>> DictOfDictByKeys(this IEnumerable<Dictionary<string, object?>> source, params string[] keys)
        {
            Dictionary<string, Dictionary<string, object?>> response = new();
            foreach (Dictionary<string, object?> row in source) {
                List<string> val = new();
                foreach (var k in keys)
                    val.Add(row[k]!.ToString()!);

                string key = String.Join("~", val.ToArray());
                response[key] = row;
            }

            return response;
        }
        public static IDictionary<T, K?> DictOfDictByKeyValue<T, K>(this IEnumerable<Dictionary<string, object?>> source, string key, string keyValue)
        {
            Dictionary<T, K?> response = new();
            foreach (Dictionary<string, object?> row in source)
                response[(T)row[key]!] = (K?)row[keyValue];

            return response;
        }

        public static IDictionary<string, object?> DictOfDictByKeysValue(this IEnumerable<Dictionary<string, object?>> source, string keyValue, params string[] keys)
        {
            Dictionary<string, object?> response = new();
            foreach (Dictionary<string, object?> row in source)
            {
                List<string> val = new();
                foreach (var k in keys)
                    val.Add(row[k]!.ToString()!);

                string key = String.Join("~", val.ToArray());
                response[key] = row[keyValue];
            }

            return response;
        }

        public static IDictionary<string, object?> AddPrefixToKeysOfDict(this IDictionary<string, object?> source, string prefix)
        {
            Dictionary<string, object?> response = new();
            foreach(var (key, obj) in source)
                response[prefix + key] = obj;

            return response;
        }

        public static List<Dictionary<string, object?>> AddPrefixToKeysOfDicts(this IEnumerable<Dictionary<string, object?>> source, string prefix)
        {
            List<Dictionary<string, object?>> response = new();
            foreach(Dictionary<string, object?> row in source)
                response.Add((Dictionary<string, object?>)row.AddPrefixToKeysOfDict(prefix));
            return response;
        }

        /// <summary>
        /// Add prefix to each element of list of strings
        /// </summary>
        /// <param name="source"></param>
        /// <param name="prefix"></param>
        /// <returns></returns>
        public static IEnumerable<string> AddPrefixToEnum(this IEnumerable<string> source, string prefix)
        {
            List<string> response = new();
            foreach (string e in source)
                response.Add(prefix + e);
            return response;
        }

        /// <summary>
        /// https://www.dotnetperls.com/sort-strings-length
        /// </summary>
        /// <param name="e"></param>
        /// <returns></returns>
        public static IEnumerable<string> SortByLength(this IEnumerable<string> e, string sort = "ASC")
        {
            // Use LINQ to sort the array received and return a copy.
            var sorted = (sort == "DESC") ?
                from s in e orderby s.Length ascending select s : from s in e orderby s.Length descending select s;

            return sorted;
        }

        public static string ToStringDict(this IDictionary<string, object?> param)
        {
            string dictionaryString = "{";
            foreach (KeyValuePair<string, object?> keyValues in param)
            {
                dictionaryString += keyValues.Key + " : " + keyValues.Value?.ToString() + ", ";
            }
            return dictionaryString.TrimEnd(',', ' ') + "}";
        }

        /// <summary>Clonar objeto </summary>
        /// <remarks>https://www.wwt.com/article/how-to-clone-objects-in-dotnet-core</remarks>
        public static T? Clone<T>(this T self)
        {
            var serialized = JsonConvert.SerializeObject(self);
            return JsonConvert.DeserializeObject<T>(serialized);
        }

        public static string ToStringKeys(this IDictionary<string, object?> param, params string[] keysToConcatenate)
        {
            return string.Join(", ", keysToConcatenate.Where(param.ContainsKey).Select(key => param[key]?.ToString() ?? ""));
        }

        public static string DictToString(this IDictionary<string, object?> dictionary)
        {
            var entries = new List<string>();

            foreach (var kvp in dictionary)
            {
                // Handle null values explicitly
                string value = kvp.Value == null ? "null" : kvp.Value.ToString();

                // For lists or collections, format them as a comma-separated list
                if (kvp.Value is IEnumerable<object> collection)
                {
                    value = "[" + string.Join(", ", collection) + "]";
                }

                entries.Add($"{kvp.Key}: {value}");
            }

            // Join the entries with commas and format as a dictionary-like string
            return "{" + string.Join(", ", entries) + "}";
        }
    }

}


   


