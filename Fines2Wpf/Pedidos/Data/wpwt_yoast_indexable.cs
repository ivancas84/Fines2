#nullable enable
using SqlOrganize;
using System;
using System.ComponentModel;
using System.Collections.Generic;
using System.Reflection;
using Utils;

namespace Fines2Wpf.Pedidos.Data
{
    public class Data_wpwt_yoast_indexable : SqlOrganize.Data
    {

        public Data_wpwt_yoast_indexable ()
        {
            Initialize();
        }

        public Data_wpwt_yoast_indexable(DataInitMode mode = DataInitMode.Default)
        {
            Initialize(mode);
        }

        protected virtual void Initialize(DataInitMode mode = DataInitMode.Default)
        {
            switch(mode)
            {
                case DataInitMode.Default:
                case DataInitMode.DefaultMain:
                    _id = (uint?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("id").Get("id");
                    _is_protected = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_protected").Get("is_protected");
                    _is_cornerstone = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_cornerstone").Get("is_cornerstone");
                    _is_robots_noindex = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_robots_noindex").Get("is_robots_noindex");
                    _is_robots_nofollow = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_robots_nofollow").Get("is_robots_nofollow");
                    _is_robots_noarchive = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_robots_noarchive").Get("is_robots_noarchive");
                    _is_robots_noimageindex = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_robots_noimageindex").Get("is_robots_noimageindex");
                    _is_robots_nosnippet = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("is_robots_nosnippet").Get("is_robots_nosnippet");
                    _updated_at = (DateTime?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("updated_at").Get("updated_at");
                    _blog_id = (long?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("blog_id").Get("blog_id");
                    _has_ancestors = (bool?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("has_ancestors").Get("has_ancestors");
                    _version = (int?)ContainerApp.db.Values("wpwt_yoast_indexable").Default("version").Get("version");
                break;
            }
        }

        public string? Label { get; set; }

        protected uint? _id = null;
        public uint? id
        {
            get { return _id; }
            set { _id = value; NotifyPropertyChanged(); }
        }
        protected string? _permalink = null;
        public string? permalink
        {
            get { return _permalink; }
            set { _permalink = value; NotifyPropertyChanged(); }
        }
        protected string? _permalink_hash = null;
        public string? permalink_hash
        {
            get { return _permalink_hash; }
            set { _permalink_hash = value; NotifyPropertyChanged(); }
        }
        protected long? _object_id = null;
        public long? object_id
        {
            get { return _object_id; }
            set { _object_id = value; NotifyPropertyChanged(); }
        }
        protected string? _object_type = null;
        public string? object_type
        {
            get { return _object_type; }
            set { _object_type = value; NotifyPropertyChanged(); }
        }
        protected string? _object_sub_type = null;
        public string? object_sub_type
        {
            get { return _object_sub_type; }
            set { _object_sub_type = value; NotifyPropertyChanged(); }
        }
        protected long? _author_id = null;
        public long? author_id
        {
            get { return _author_id; }
            set { _author_id = value; NotifyPropertyChanged(); }
        }
        protected long? _post_parent = null;
        public long? post_parent
        {
            get { return _post_parent; }
            set { _post_parent = value; NotifyPropertyChanged(); }
        }
        protected string? _title = null;
        public string? title
        {
            get { return _title; }
            set { _title = value; NotifyPropertyChanged(); }
        }
        protected string? _description = null;
        public string? description
        {
            get { return _description; }
            set { _description = value; NotifyPropertyChanged(); }
        }
        protected string? _breadcrumb_title = null;
        public string? breadcrumb_title
        {
            get { return _breadcrumb_title; }
            set { _breadcrumb_title = value; NotifyPropertyChanged(); }
        }
        protected string? _post_status = null;
        public string? post_status
        {
            get { return _post_status; }
            set { _post_status = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_public = null;
        public bool? is_public
        {
            get { return _is_public; }
            set { _is_public = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_protected = null;
        public bool? is_protected
        {
            get { return _is_protected; }
            set { _is_protected = value; NotifyPropertyChanged(); }
        }
        protected bool? _has_public_posts = null;
        public bool? has_public_posts
        {
            get { return _has_public_posts; }
            set { _has_public_posts = value; NotifyPropertyChanged(); }
        }
        protected uint? _number_of_pages = null;
        public uint? number_of_pages
        {
            get { return _number_of_pages; }
            set { _number_of_pages = value; NotifyPropertyChanged(); }
        }
        protected string? _canonical = null;
        public string? canonical
        {
            get { return _canonical; }
            set { _canonical = value; NotifyPropertyChanged(); }
        }
        protected string? _primary_focus_keyword = null;
        public string? primary_focus_keyword
        {
            get { return _primary_focus_keyword; }
            set { _primary_focus_keyword = value; NotifyPropertyChanged(); }
        }
        protected int? _primary_focus_keyword_score = null;
        public int? primary_focus_keyword_score
        {
            get { return _primary_focus_keyword_score; }
            set { _primary_focus_keyword_score = value; NotifyPropertyChanged(); }
        }
        protected int? _readability_score = null;
        public int? readability_score
        {
            get { return _readability_score; }
            set { _readability_score = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_cornerstone = null;
        public bool? is_cornerstone
        {
            get { return _is_cornerstone; }
            set { _is_cornerstone = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_robots_noindex = null;
        public bool? is_robots_noindex
        {
            get { return _is_robots_noindex; }
            set { _is_robots_noindex = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_robots_nofollow = null;
        public bool? is_robots_nofollow
        {
            get { return _is_robots_nofollow; }
            set { _is_robots_nofollow = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_robots_noarchive = null;
        public bool? is_robots_noarchive
        {
            get { return _is_robots_noarchive; }
            set { _is_robots_noarchive = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_robots_noimageindex = null;
        public bool? is_robots_noimageindex
        {
            get { return _is_robots_noimageindex; }
            set { _is_robots_noimageindex = value; NotifyPropertyChanged(); }
        }
        protected bool? _is_robots_nosnippet = null;
        public bool? is_robots_nosnippet
        {
            get { return _is_robots_nosnippet; }
            set { _is_robots_nosnippet = value; NotifyPropertyChanged(); }
        }
        protected string? _twitter_title = null;
        public string? twitter_title
        {
            get { return _twitter_title; }
            set { _twitter_title = value; NotifyPropertyChanged(); }
        }
        protected string? _twitter_image = null;
        public string? twitter_image
        {
            get { return _twitter_image; }
            set { _twitter_image = value; NotifyPropertyChanged(); }
        }
        protected string? _twitter_description = null;
        public string? twitter_description
        {
            get { return _twitter_description; }
            set { _twitter_description = value; NotifyPropertyChanged(); }
        }
        protected string? _twitter_image_id = null;
        public string? twitter_image_id
        {
            get { return _twitter_image_id; }
            set { _twitter_image_id = value; NotifyPropertyChanged(); }
        }
        protected string? _twitter_image_source = null;
        public string? twitter_image_source
        {
            get { return _twitter_image_source; }
            set { _twitter_image_source = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_title = null;
        public string? open_graph_title
        {
            get { return _open_graph_title; }
            set { _open_graph_title = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_description = null;
        public string? open_graph_description
        {
            get { return _open_graph_description; }
            set { _open_graph_description = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_image = null;
        public string? open_graph_image
        {
            get { return _open_graph_image; }
            set { _open_graph_image = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_image_id = null;
        public string? open_graph_image_id
        {
            get { return _open_graph_image_id; }
            set { _open_graph_image_id = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_image_source = null;
        public string? open_graph_image_source
        {
            get { return _open_graph_image_source; }
            set { _open_graph_image_source = value; NotifyPropertyChanged(); }
        }
        protected string? _open_graph_image_meta = null;
        public string? open_graph_image_meta
        {
            get { return _open_graph_image_meta; }
            set { _open_graph_image_meta = value; NotifyPropertyChanged(); }
        }
        protected int? _link_count = null;
        public int? link_count
        {
            get { return _link_count; }
            set { _link_count = value; NotifyPropertyChanged(); }
        }
        protected int? _incoming_link_count = null;
        public int? incoming_link_count
        {
            get { return _incoming_link_count; }
            set { _incoming_link_count = value; NotifyPropertyChanged(); }
        }
        protected uint? _prominent_words_version = null;
        public uint? prominent_words_version
        {
            get { return _prominent_words_version; }
            set { _prominent_words_version = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _created_at = null;
        public DateTime? created_at
        {
            get { return _created_at; }
            set { _created_at = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _updated_at = null;
        public DateTime? updated_at
        {
            get { return _updated_at; }
            set { _updated_at = value; NotifyPropertyChanged(); }
        }
        protected long? _blog_id = null;
        public long? blog_id
        {
            get { return _blog_id; }
            set { _blog_id = value; NotifyPropertyChanged(); }
        }
        protected string? _language = null;
        public string? language
        {
            get { return _language; }
            set { _language = value; NotifyPropertyChanged(); }
        }
        protected string? _region = null;
        public string? region
        {
            get { return _region; }
            set { _region = value; NotifyPropertyChanged(); }
        }
        protected string? _schema_page_type = null;
        public string? schema_page_type
        {
            get { return _schema_page_type; }
            set { _schema_page_type = value; NotifyPropertyChanged(); }
        }
        protected string? _schema_article_type = null;
        public string? schema_article_type
        {
            get { return _schema_article_type; }
            set { _schema_article_type = value; NotifyPropertyChanged(); }
        }
        protected bool? _has_ancestors = null;
        public bool? has_ancestors
        {
            get { return _has_ancestors; }
            set { _has_ancestors = value; NotifyPropertyChanged(); }
        }
        protected int? _estimated_reading_time_minutes = null;
        public int? estimated_reading_time_minutes
        {
            get { return _estimated_reading_time_minutes; }
            set { _estimated_reading_time_minutes = value; NotifyPropertyChanged(); }
        }
        protected int? _version = null;
        public int? version
        {
            get { return _version; }
            set { _version = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _object_last_modified = null;
        public DateTime? object_last_modified
        {
            get { return _object_last_modified; }
            set { _object_last_modified = value; NotifyPropertyChanged(); }
        }
        protected DateTime? _object_published_at = null;
        public DateTime? object_published_at
        {
            get { return _object_published_at; }
            set { _object_published_at = value; NotifyPropertyChanged(); }
        }
        protected override string ValidateField(string columnName)
        {

            switch (columnName)
            {

                case "id":
                    if (_id == null)
                        return "Debe completar valor.";
                    return "";

                case "permalink":
                    return "";

                case "permalink_hash":
                    return "";

                case "object_id":
                    return "";

                case "object_type":
                    if (_object_type == null)
                        return "Debe completar valor.";
                    return "";

                case "object_sub_type":
                    return "";

                case "author_id":
                    return "";

                case "post_parent":
                    return "";

                case "title":
                    return "";

                case "description":
                    return "";

                case "breadcrumb_title":
                    return "";

                case "post_status":
                    return "";

                case "is_public":
                    return "";

                case "is_protected":
                    return "";

                case "has_public_posts":
                    return "";

                case "number_of_pages":
                    return "";

                case "canonical":
                    return "";

                case "primary_focus_keyword":
                    return "";

                case "primary_focus_keyword_score":
                    return "";

                case "readability_score":
                    return "";

                case "is_cornerstone":
                    return "";

                case "is_robots_noindex":
                    return "";

                case "is_robots_nofollow":
                    return "";

                case "is_robots_noarchive":
                    return "";

                case "is_robots_noimageindex":
                    return "";

                case "is_robots_nosnippet":
                    return "";

                case "twitter_title":
                    return "";

                case "twitter_image":
                    return "";

                case "twitter_description":
                    return "";

                case "twitter_image_id":
                    return "";

                case "twitter_image_source":
                    return "";

                case "open_graph_title":
                    return "";

                case "open_graph_description":
                    return "";

                case "open_graph_image":
                    return "";

                case "open_graph_image_id":
                    return "";

                case "open_graph_image_source":
                    return "";

                case "open_graph_image_meta":
                    return "";

                case "link_count":
                    return "";

                case "incoming_link_count":
                    return "";

                case "prominent_words_version":
                    return "";

                case "created_at":
                    return "";

                case "updated_at":
                    if (_updated_at == null)
                        return "Debe completar valor.";
                    return "";

                case "blog_id":
                    if (_blog_id == null)
                        return "Debe completar valor.";
                    return "";

                case "language":
                    return "";

                case "region":
                    return "";

                case "schema_page_type":
                    return "";

                case "schema_article_type":
                    return "";

                case "has_ancestors":
                    return "";

                case "estimated_reading_time_minutes":
                    return "";

                case "version":
                    return "";

                case "object_last_modified":
                    return "";

                case "object_published_at":
                    return "";

            }

            return "";
        }
    }
}
