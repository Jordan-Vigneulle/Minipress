enum ArticleSort { dateDesc, dateAsc, auteur }

extension ArticleSortParam on ArticleSort {
  String get apiValue {
    switch (this) {
      case ArticleSort.dateDesc:
        return 'date-desc';
      case ArticleSort.dateAsc:
        return 'date-asc';
      case ArticleSort.auteur:
        return 'auteur';
    }
  }
}
