// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package query

import (
	"context"
	"database/sql"

	"gorm.io/gorm"

	"gorm.io/gen"

	"gorm.io/plugin/dbresolver"
)

func Use(db *gorm.DB, opts ...gen.DOOption) *Query {
	return &Query{
		db:        db,
		Album:     newAlbum(db, opts...),
		Article:   newArticle(db, opts...),
		Category:  newCategory(db, opts...),
		Cosplayer: newCosplayer(db, opts...),
		Medium:    newMedium(db, opts...),
	}
}

type Query struct {
	db *gorm.DB

	Album     album
	Article   article
	Category  category
	Cosplayer cosplayer
	Medium    medium
}

func (q *Query) Available() bool { return q.db != nil }

func (q *Query) clone(db *gorm.DB) *Query {
	return &Query{
		db:        db,
		Album:     q.Album.clone(db),
		Article:   q.Article.clone(db),
		Category:  q.Category.clone(db),
		Cosplayer: q.Cosplayer.clone(db),
		Medium:    q.Medium.clone(db),
	}
}

func (q *Query) ReadDB() *Query {
	return q.ReplaceDB(q.db.Clauses(dbresolver.Read))
}

func (q *Query) WriteDB() *Query {
	return q.ReplaceDB(q.db.Clauses(dbresolver.Write))
}

func (q *Query) ReplaceDB(db *gorm.DB) *Query {
	return &Query{
		db:        db,
		Album:     q.Album.replaceDB(db),
		Article:   q.Article.replaceDB(db),
		Category:  q.Category.replaceDB(db),
		Cosplayer: q.Cosplayer.replaceDB(db),
		Medium:    q.Medium.replaceDB(db),
	}
}

type queryCtx struct {
	Album     *albumDo
	Article   *articleDo
	Category  *categoryDo
	Cosplayer *cosplayerDo
	Medium    *mediumDo
}

func (q *Query) WithContext(ctx context.Context) *queryCtx {
	return &queryCtx{
		Album:     q.Album.WithContext(ctx),
		Article:   q.Article.WithContext(ctx),
		Category:  q.Category.WithContext(ctx),
		Cosplayer: q.Cosplayer.WithContext(ctx),
		Medium:    q.Medium.WithContext(ctx),
	}
}

func (q *Query) Transaction(fc func(tx *Query) error, opts ...*sql.TxOptions) error {
	return q.db.Transaction(func(tx *gorm.DB) error { return fc(q.clone(tx)) }, opts...)
}

func (q *Query) Begin(opts ...*sql.TxOptions) *QueryTx {
	tx := q.db.Begin(opts...)
	return &QueryTx{Query: q.clone(tx), Error: tx.Error}
}

type QueryTx struct {
	*Query
	Error error
}

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
