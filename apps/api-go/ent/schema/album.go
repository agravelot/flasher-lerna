package schema

import (
	"entgo.io/ent"
	"entgo.io/ent/schema"
	"entgo.io/ent/schema/edge"
	"entgo.io/ent/schema/field"
)

type Album struct {
	ent.Schema
}

func (Album) Fields() []ent.Field {
	return []ent.Field{field.Int32("id"), field.String("slug").Unique(), field.String("title").Unique(), field.String("body").Optional(), field.Time("published_at").Optional(), field.Bool("private"), field.Int("user_id").Optional(), field.Time("created_at").Optional(), field.Time("updated_at").Optional(), field.Bool("notify_users_on_published"), field.String("meta_description")}
}
func (Album) Edges() []ent.Edge {
	return []ent.Edge{edge.To("album_categories", AlbumCategory.Type), edge.To("album_cosplayers", AlbumCosplayer.Type),
		edge.To("categories", Category.Type),
	}
}
func (Album) Annotations() []schema.Annotation {
	return nil
}
