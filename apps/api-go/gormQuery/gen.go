// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package gormQuery

import (
	"context"
	"database/sql"

	"gorm.io/gorm"
)

func Use(db *gorm.DB) *Query {
	return &Query{
		db:       db,
		Album:    newAlbum(db),
		Article:  newArticle(db),
		Category: newCategory(db),
		Medium:   newMedium(db),
	}
}

type Query struct {
	db *gorm.DB

	Album    album
	Article  article
	Category category
	Medium   medium
}

func (q *Query) Available() bool { return q.db != nil }

func (q *Query) clone(db *gorm.DB) *Query {
	return &Query{
		db:       db,
		Album:    q.Album.clone(db),
		Article:  q.Article.clone(db),
		Category: q.Category.clone(db),
		Medium:   q.Medium.clone(db),
	}
}

type queryCtx struct {
	Album    albumDo
	Article  articleDo
	Category categoryDo
	Medium   mediumDo
}

func (q *Query) WithContext(ctx context.Context) *queryCtx {
	return &queryCtx{
		Album:    *q.Album.WithContext(ctx),
		Article:  *q.Article.WithContext(ctx),
		Category: *q.Category.WithContext(ctx),
		Medium:   *q.Medium.WithContext(ctx),
	}
}

func (q *Query) Transaction(fc func(tx *Query) error, opts ...*sql.TxOptions) error {
	return q.db.Transaction(func(tx *gorm.DB) error { return fc(q.clone(tx)) }, opts...)
}

func (q *Query) Begin(opts ...*sql.TxOptions) *QueryTx {
	return &QueryTx{q.clone(q.db.Begin(opts...))}
}

type QueryTx struct{ *Query }

func (q *QueryTx) Commit() error {
	return q.db.Commit().Error
}

func (q *QueryTx) Rollback() error {
	return q.db.Rollback().Error
}

func (q *QueryTx) SavePoint(name string) error {
	return q.db.SavePoint(name).Error
}

func (q *QueryTx) RollbackTo(name string) error {
	return q.db.RollbackTo(name).Error
}