// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package gormQuery

import (
	"context"

	"gorm.io/gorm"
	"gorm.io/gorm/clause"
	"gorm.io/gorm/schema"

	"api-go/model"

	"gorm.io/gen"
	"gorm.io/gen/field"
)

func newMedium(db *gorm.DB) medium {
	_medium := medium{}

	_medium.mediumDo.UseDB(db)
	_medium.mediumDo.UseModel(&model.Medium{})

	tableName := _medium.mediumDo.TableName()
	_medium.ALL = field.NewField(tableName, "*")
	_medium.ID = field.NewInt32(tableName, "id")
	_medium.ModelType = field.NewString(tableName, "model_type")
	_medium.ModelID = field.NewInt64(tableName, "model_id")
	_medium.CollectionName = field.NewString(tableName, "collection_name")
	_medium.Name = field.NewString(tableName, "name")
	_medium.FileName = field.NewString(tableName, "file_name")
	_medium.MimeType = field.NewString(tableName, "mime_type")
	_medium.Disk = field.NewString(tableName, "disk")
	_medium.Size = field.NewInt64(tableName, "size")
	_medium.Manipulations = field.NewString(tableName, "manipulations")
	_medium.CustomProperties = field.NewField(tableName, "custom_properties")
	_medium.ResponsiveImages = field.NewField(tableName, "responsive_images")
	_medium.OrderColumn = field.NewInt32(tableName, "order_column")
	_medium.CreatedAt = field.NewTime(tableName, "created_at")
	_medium.UpdatedAt = field.NewTime(tableName, "updated_at")
	_medium.UUID = field.NewString(tableName, "uuid")
	_medium.ConversionsDisk = field.NewString(tableName, "conversions_disk")

	_medium.fillFieldMap()

	return _medium
}

type medium struct {
	mediumDo mediumDo

	ALL              field.Field
	ID               field.Int32
	ModelType        field.String
	ModelID          field.Int64
	CollectionName   field.String
	Name             field.String
	FileName         field.String
	MimeType         field.String
	Disk             field.String
	Size             field.Int64
	Manipulations    field.String
	CustomProperties field.Field
	ResponsiveImages field.Field
	OrderColumn      field.Int32
	CreatedAt        field.Time
	UpdatedAt        field.Time
	UUID             field.String
	ConversionsDisk  field.String

	fieldMap map[string]field.Expr
}

func (m medium) Table(newTableName string) *medium {
	m.mediumDo.UseTable(newTableName)
	return m.updateTableName(newTableName)
}

func (m medium) As(alias string) *medium {
	m.mediumDo.DO = *(m.mediumDo.As(alias).(*gen.DO))
	return m.updateTableName(alias)
}

func (m *medium) updateTableName(table string) *medium {
	m.ALL = field.NewField(table, "*")
	m.ID = field.NewInt32(table, "id")
	m.ModelType = field.NewString(table, "model_type")
	m.ModelID = field.NewInt64(table, "model_id")
	m.CollectionName = field.NewString(table, "collection_name")
	m.Name = field.NewString(table, "name")
	m.FileName = field.NewString(table, "file_name")
	m.MimeType = field.NewString(table, "mime_type")
	m.Disk = field.NewString(table, "disk")
	m.Size = field.NewInt64(table, "size")
	m.Manipulations = field.NewString(table, "manipulations")
	m.CustomProperties = field.NewField(table, "custom_properties")
	m.ResponsiveImages = field.NewField(table, "responsive_images")
	m.OrderColumn = field.NewInt32(table, "order_column")
	m.CreatedAt = field.NewTime(table, "created_at")
	m.UpdatedAt = field.NewTime(table, "updated_at")
	m.UUID = field.NewString(table, "uuid")
	m.ConversionsDisk = field.NewString(table, "conversions_disk")

	m.fillFieldMap()

	return m
}

func (m *medium) WithContext(ctx context.Context) *mediumDo { return m.mediumDo.WithContext(ctx) }

func (m medium) TableName() string { return m.mediumDo.TableName() }

func (m *medium) GetFieldByName(fieldName string) (field.OrderExpr, bool) {
	_f, ok := m.fieldMap[fieldName]
	if !ok || _f == nil {
		return nil, false
	}
	_oe, ok := _f.(field.OrderExpr)
	return _oe, ok
}

func (m *medium) fillFieldMap() {
	m.fieldMap = make(map[string]field.Expr, 17)
	m.fieldMap["id"] = m.ID
	m.fieldMap["model_type"] = m.ModelType
	m.fieldMap["model_id"] = m.ModelID
	m.fieldMap["collection_name"] = m.CollectionName
	m.fieldMap["name"] = m.Name
	m.fieldMap["file_name"] = m.FileName
	m.fieldMap["mime_type"] = m.MimeType
	m.fieldMap["disk"] = m.Disk
	m.fieldMap["size"] = m.Size
	m.fieldMap["manipulations"] = m.Manipulations
	m.fieldMap["custom_properties"] = m.CustomProperties
	m.fieldMap["responsive_images"] = m.ResponsiveImages
	m.fieldMap["order_column"] = m.OrderColumn
	m.fieldMap["created_at"] = m.CreatedAt
	m.fieldMap["updated_at"] = m.UpdatedAt
	m.fieldMap["uuid"] = m.UUID
	m.fieldMap["conversions_disk"] = m.ConversionsDisk
}

func (m medium) clone(db *gorm.DB) medium {
	m.mediumDo.ReplaceDB(db)
	return m
}

type mediumDo struct{ gen.DO }

func (m mediumDo) Debug() *mediumDo {
	return m.withDO(m.DO.Debug())
}

func (m mediumDo) WithContext(ctx context.Context) *mediumDo {
	return m.withDO(m.DO.WithContext(ctx))
}

func (m mediumDo) Clauses(conds ...clause.Expression) *mediumDo {
	return m.withDO(m.DO.Clauses(conds...))
}

func (m mediumDo) Returning(value interface{}, columns ...string) *mediumDo {
	return m.withDO(m.DO.Returning(value, columns...))
}

func (m mediumDo) Not(conds ...gen.Condition) *mediumDo {
	return m.withDO(m.DO.Not(conds...))
}

func (m mediumDo) Or(conds ...gen.Condition) *mediumDo {
	return m.withDO(m.DO.Or(conds...))
}

func (m mediumDo) Select(conds ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Select(conds...))
}

func (m mediumDo) Where(conds ...gen.Condition) *mediumDo {
	return m.withDO(m.DO.Where(conds...))
}

func (m mediumDo) Exists(subquery interface{ UnderlyingDB() *gorm.DB }) *mediumDo {
	return m.Where(field.CompareSubQuery(field.ExistsOp, nil, subquery.UnderlyingDB()))
}

func (m mediumDo) Order(conds ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Order(conds...))
}

func (m mediumDo) Distinct(cols ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Distinct(cols...))
}

func (m mediumDo) Omit(cols ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Omit(cols...))
}

func (m mediumDo) Join(table schema.Tabler, on ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Join(table, on...))
}

func (m mediumDo) LeftJoin(table schema.Tabler, on ...field.Expr) *mediumDo {
	return m.withDO(m.DO.LeftJoin(table, on...))
}

func (m mediumDo) RightJoin(table schema.Tabler, on ...field.Expr) *mediumDo {
	return m.withDO(m.DO.RightJoin(table, on...))
}

func (m mediumDo) Group(cols ...field.Expr) *mediumDo {
	return m.withDO(m.DO.Group(cols...))
}

func (m mediumDo) Having(conds ...gen.Condition) *mediumDo {
	return m.withDO(m.DO.Having(conds...))
}

func (m mediumDo) Limit(limit int) *mediumDo {
	return m.withDO(m.DO.Limit(limit))
}

func (m mediumDo) Offset(offset int) *mediumDo {
	return m.withDO(m.DO.Offset(offset))
}

func (m mediumDo) Scopes(funcs ...func(gen.Dao) gen.Dao) *mediumDo {
	return m.withDO(m.DO.Scopes(funcs...))
}

func (m mediumDo) Unscoped() *mediumDo {
	return m.withDO(m.DO.Unscoped())
}

func (m mediumDo) Create(values ...*model.Medium) error {
	if len(values) == 0 {
		return nil
	}
	return m.DO.Create(values)
}

func (m mediumDo) CreateInBatches(values []*model.Medium, batchSize int) error {
	return m.DO.CreateInBatches(values, batchSize)
}

// Save : !!! underlying implementation is different with GORM
// The method is equivalent to executing the statement: db.Clauses(clause.OnConflict{UpdateAll: true}).Create(values)
func (m mediumDo) Save(values ...*model.Medium) error {
	if len(values) == 0 {
		return nil
	}
	return m.DO.Save(values)
}

func (m mediumDo) First() (*model.Medium, error) {
	if result, err := m.DO.First(); err != nil {
		return nil, err
	} else {
		return result.(*model.Medium), nil
	}
}

func (m mediumDo) Take() (*model.Medium, error) {
	if result, err := m.DO.Take(); err != nil {
		return nil, err
	} else {
		return result.(*model.Medium), nil
	}
}

func (m mediumDo) Last() (*model.Medium, error) {
	if result, err := m.DO.Last(); err != nil {
		return nil, err
	} else {
		return result.(*model.Medium), nil
	}
}

func (m mediumDo) Find() ([]*model.Medium, error) {
	result, err := m.DO.Find()
	return result.([]*model.Medium), err
}

func (m mediumDo) FindInBatch(batchSize int, fc func(tx gen.Dao, batch int) error) (results []*model.Medium, err error) {
	buf := make([]*model.Medium, 0, batchSize)
	err = m.DO.FindInBatches(&buf, batchSize, func(tx gen.Dao, batch int) error {
		defer func() { results = append(results, buf...) }()
		return fc(tx, batch)
	})
	return results, err
}

func (m mediumDo) FindInBatches(result *[]*model.Medium, batchSize int, fc func(tx gen.Dao, batch int) error) error {
	return m.DO.FindInBatches(result, batchSize, fc)
}

func (m mediumDo) Attrs(attrs ...field.AssignExpr) *mediumDo {
	return m.withDO(m.DO.Attrs(attrs...))
}

func (m mediumDo) Assign(attrs ...field.AssignExpr) *mediumDo {
	return m.withDO(m.DO.Assign(attrs...))
}

func (m mediumDo) Joins(field field.RelationField) *mediumDo {
	return m.withDO(m.DO.Joins(field))
}

func (m mediumDo) Preload(field field.RelationField) *mediumDo {
	return m.withDO(m.DO.Preload(field))
}

func (m mediumDo) FirstOrInit() (*model.Medium, error) {
	if result, err := m.DO.FirstOrInit(); err != nil {
		return nil, err
	} else {
		return result.(*model.Medium), nil
	}
}

func (m mediumDo) FirstOrCreate() (*model.Medium, error) {
	if result, err := m.DO.FirstOrCreate(); err != nil {
		return nil, err
	} else {
		return result.(*model.Medium), nil
	}
}

func (m mediumDo) FindByPage(offset int, limit int) (result []*model.Medium, count int64, err error) {
	if limit <= 0 {
		count, err = m.Count()
		return
	}

	result, err = m.Offset(offset).Limit(limit).Find()
	if err != nil {
		return
	}

	if size := len(result); 0 < size && size < limit {
		count = int64(size + offset)
		return
	}

	count, err = m.Offset(-1).Limit(-1).Count()
	return
}

func (m mediumDo) ScanByPage(result interface{}, offset int, limit int) (count int64, err error) {
	count, err = m.Count()
	if err != nil {
		return
	}

	err = m.Offset(offset).Limit(limit).Scan(result)
	return
}

func (m *mediumDo) withDO(do gen.Dao) *mediumDo {
	m.DO = *do.(*gen.DO)
	return m
}
