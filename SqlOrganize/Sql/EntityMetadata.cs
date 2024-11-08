namespace SqlOrganize.Sql
{
    public class EntityMetadata
    {
        public Db db { get; set; }

        public string name { get; set; }
        
        public string alias { get; set; }

        public string? schema { get; set;  }

        public List<string> pk { get; set; } = new();
        public List<string> fieldNames => fields.Keys.ToList();
        public List<string> fk { get; set; } = new();

        protected List<Field> _ref; //ref

        protected List<Field> _oor; //one to one (fk in ref)

        protected List<Field> _om; //one to many

        /// <summary> Array dinamico para identificar a la entidad en un momento determinado </summary>
        /// <example> ["fecha_anio", "fecha_semestre","persona__numero_documento"] </example>
        public List<string> identifier { get; set; } = new();

        /// <summary> Valores por defecto para ordenamiento </summary>
        /// <example> ["field1"=>"asc","field2"=>"desc",...]; </example>
        public Dictionary<string, string> orderDefault { get; set; } = new();

        /// <summary> nombres de campos no administrables </summary>
        /// <remarks> los campos no insertables son aquellos que reciben se asignan directamente desde el servidor sql </remarks>
        public List<string> noAdmin { get; set; } = new();


        /// <summary> Valores principales </summary>
        public List<string> main { get; set; } = new();

        /// <summary> Valores unicos </summary>
        /// <remarks> Una entidad puede tener varios campos que determinen un valor único </remarks>
        /// <example> field1, field2, field3, ... </example>
        public List<string> unique { get; set; } = new();

        /// <summary> Valores no nulos </summary>
        public List<string> notNull { get; set; } = new();

        /// <summary> Valores unicos multiples </summary>
        /// <remarks> Cada juego de valores unicos multiples se define como una Lista </remarks>
        public List<List<string>> uniqueMultiple { get; set; } = new();

        public Dictionary<string, EntityTree> tree { get; set; } = new();

        public Dictionary<string, EntityRelation> relations { get; set; } = new();

        public Dictionary<string, EntityRef> oo { get; set; } = new();
        public Dictionary<string, EntityRef> om { get; set; } = new();


        public string schema_ => String.IsNullOrEmpty(schema) ? schema : "";
        public string schemaName => schema + name;
        public string schemaNameAlias => schema + name + " AS " + alias;

        /*
        Campo de identificacion
        *  Si existe un solo campo pk, entonces la pk sera el id. 
        *  Si existe al menos un campo unique not null, se toma como id.     
        *  Si existe multiples campos pk, se toman la concatenacion como id. 
        *  Si existe multiples campos uniqueMultiple, se toman la concatenacion como id. 
        */
        public List<string> id { get; set; }


        public Dictionary<string, Field> fields { get; set; } = new();

        protected List<Field> _Fields(List<string> fieldNames)
        {
            List<Field> fields = new();
            foreach (string fieldName in fieldNames)
                fields.Add(db.Field(name, fieldName));

            return fields;

        }

        /*
        fields no fk
        */
        public List<Field> Fields() => _Fields(fieldNames);

        /*
        fields many to one
        */
        public List<Field> FieldsFk() => _Fields(fk);

        public List<Field> FieldsRef()
        {

            if (_ref != null)
                return _ref;

            _ref = new();
            _oor = new();
            _om = new();

            foreach (var (entityName, entity) in db.entities)
            {
                foreach(var fieldName in entity.fk)
                {
                    Field field = db.Field(entityName, fieldName);
                    if (field.refEntityName!.Equals(name))
                    {
                        _ref.Add(field);
                        if (entity.unique.Contains(field.name))
                            _oor.Add(field);
                        else
                            _om.Add(field);
                    }
                }
            }

            return _ref;
        }

        public List<Field> FieldsOor()
        {

            if (_oor != null)
                return _oor;

            FieldsRef();

            return _oor!;
        }

        public List<Field> FieldsOm()
        {

            if (_om != null)
                return _om;

            FieldsRef();

            return _om!;
        }


    }
}
