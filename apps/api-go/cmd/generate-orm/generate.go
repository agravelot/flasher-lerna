package main

import (
	"gorm.io/driver/postgres"
	"gorm.io/gen"
	"gorm.io/gen/field"
	"gorm.io/gorm"
)

// generate code
func main() {
	// specify the output directory (default: "./query")
	// ### if you want to query without context constrain, set mode gen.WithoutContext ###
	g := gen.NewGenerator(gen.Config{
		OutPath: "./query",
		/* Mode: gen.WithoutContext|gen.WithDefaultQuery*/
		// if you want the nullable field generation property to be pointer type, set FieldNullable true
		FieldNullable: true,
		// if you want to assign field which has default value in `Create` API, set FieldCoverable true, reference: https://gorm.io/docs/create.html#Default-Values
		FieldCoverable: true,
		// if you want to generate index tags from database, set FieldWithIndexTag true
		FieldWithIndexTag: true,
		// if you want to generate type tags from database, set FieldWithTypeTag true
		FieldWithTypeTag: true,
		// if you need unit tests for query code, set WithUnitTest true
		WithUnitTest: false,
	})

	// dataMap := map[string]func(detailType string) (dataType string){
	// 	"integer": func(detailType string) (dataType string) { return "int64" },
	// 	"int":     func(detailType string) (dataType string) { return "int64" },
	// 	// bool mapping
	// 	"tinyint": func(detailType string) (dataType string) {
	// 		if strings.HasPrefix(detailType, "tinyint(1)") {
	// 			return "bool"
	// 		}
	// 		return "int8"
	// 	},
	// }

	// g.WithDataTypeMap(dataMap)

	// reuse the database connection in Project or create a connection here
	// if you want to use GenerateModel/GenerateModelAs, UseDB is necessary, or it will panic
	db, _ := gorm.Open(postgres.Open("host=localhost user=flasher password=flasher dbname=flasher port=5432 sslmode=disable"))
	g.UseDB(db)

	medias := g.GenerateModel(
		"media",
		gen.FieldType("custom_properties", "*CustomProperties"),
		gen.FieldType("responsive_images", "*ResponsiveImages"),
	)
	categories := g.GenerateModel("categories")
	cosplayers := g.GenerateModel("cosplayers")
	albums := g.GenerateModel(
		"albums",
		gen.FieldRelate(field.HasMany, "Categories", categories,
			&field.RelateConfig{
				GORMTag: map[string][]string{
					"many2many":      {"album_category"},
					"joinForeignKey": {"AlbumID"},
					"joinReferences": {"CategoryID"},
				},
			},
		),
		gen.FieldRelate(field.HasMany, "Medias", medias,
			&field.RelateConfig{
				// RelateSlice: true,
				GORMTag: map[string][]string{
					"polymorphic":      {"Model"},
					"polymorphicValue": {"App\\\\Models\\\\Album"},
				},
			},
		),
		gen.FieldRelate(field.HasMany, "Cosplayers", cosplayers,
			&field.RelateConfig{
				GORMTag: map[string][]string{
					"many2many":      {"album_cosaplyer"},
					"joinForeignKey": {"AlbumID"},
					"joinReferences": {"CosplayerID"},
				},
			},
		),
	)

	// Add reverse relation
	categories = g.GenerateModel("categories",
		gen.FieldRelate(field.HasMany, "Albums", albums,
			&field.RelateConfig{
				GORMTag: map[string][]string{
					"many2many":      {"album_category"},
					"joinForeignKey": {"CategoryID"},
					"joinReferences": {"AlbumID"},
				},
			},
		))

	// Add reverse relation
	cosplayers = g.GenerateModel("cosplayers",
		gen.FieldRelate(field.HasMany, "Albums", albums,
			&field.RelateConfig{
				GORMTag: map[string][]string{
					"many2many":      {"album_cosaplyer"},
					"joinForeignKey": {"CosplayerID"},
					"joinReferences": {"AlbumID"},
				},
			},
		),
	)

	article := g.GenerateModel("articles")

	// apply basic crud api on structs or table models which is specified by table name with function
	// GenerateModel/GenerateModelAs. And generator will generate table models' code when calling Excute.
	g.ApplyBasic(
		medias,
		categories,
		cosplayers,
		albums,
		article,
	)

	// apply diy interfaces on structs or table models
	// g.ApplyInterface(func(method model.Method) {}, model.User{}, g.GenerateModel("company"))

	// execute the action of code generation
	g.Execute()
}
