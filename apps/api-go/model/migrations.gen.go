// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

const TableNameMigration = "migrations"

// Migration mapped from table <migrations>
type Migration struct {
	ID        string `gorm:"column:id;type:;primaryKey;autoIncrement:true" json:"id"`
	Migration string `gorm:"column:migration;type:;not null" json:"migration"`
	Batch     string `gorm:"column:batch;type:;not null" json:"batch"`
}

// TableName Migration's table name
func (*Migration) TableName() string {
	return TableNameMigration
}
