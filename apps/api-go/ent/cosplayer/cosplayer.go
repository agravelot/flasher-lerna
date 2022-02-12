// Code generated by entc, DO NOT EDIT.

package cosplayer

const (
	// Label holds the string label denoting the cosplayer type in the database.
	Label = "cosplayer"
	// FieldID holds the string denoting the id field in the database.
	FieldID = "id"
	// FieldName holds the string denoting the name field in the database.
	FieldName = "name"
	// FieldSlug holds the string denoting the slug field in the database.
	FieldSlug = "slug"
	// FieldDescription holds the string denoting the description field in the database.
	FieldDescription = "description"
	// FieldPicture holds the string denoting the picture field in the database.
	FieldPicture = "picture"
	// FieldUserID holds the string denoting the user_id field in the database.
	FieldUserID = "user_id"
	// FieldCreatedAt holds the string denoting the created_at field in the database.
	FieldCreatedAt = "created_at"
	// FieldUpdatedAt holds the string denoting the updated_at field in the database.
	FieldUpdatedAt = "updated_at"
	// EdgeAlbumCosplayers holds the string denoting the album_cosplayers edge name in mutations.
	EdgeAlbumCosplayers = "album_cosplayers"
	// Table holds the table name of the cosplayer in the database.
	Table = "cosplayers"
	// AlbumCosplayersTable is the table that holds the album_cosplayers relation/edge.
	AlbumCosplayersTable = "album_cosplayer"
	// AlbumCosplayersInverseTable is the table name for the AlbumCosplayer entity.
	// It exists in this package in order to avoid circular dependency with the "albumcosplayer" package.
	AlbumCosplayersInverseTable = "album_cosplayer"
	// AlbumCosplayersColumn is the table column denoting the album_cosplayers relation/edge.
	AlbumCosplayersColumn = "cosplayer_id"
)

// Columns holds all SQL columns for cosplayer fields.
var Columns = []string{
	FieldID,
	FieldName,
	FieldSlug,
	FieldDescription,
	FieldPicture,
	FieldUserID,
	FieldCreatedAt,
	FieldUpdatedAt,
}

// ValidColumn reports if the column name is valid (part of the table columns).
func ValidColumn(column string) bool {
	for i := range Columns {
		if column == Columns[i] {
			return true
		}
	}
	return false
}
