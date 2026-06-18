import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../modeles/articleSort.dart';
import '../modeles/articleList.dart';
import '../modeles/articleDetail.dart';
import '../service/service_api.dart';

// Liste des articles
final articlesProvider = FutureProvider.family<List<ArticleList>, ArticleSort>((
  ref,
  sort,
) async {
  return await articleService.getArticles(sort: sort.apiValue);
});

// Détail article
final articleDetailProvider = FutureProvider.family<ArticleDetail, String>((
  ref,
  uri,
) async {
  return await articleService.getArticleByUri(uri);
});

// Articles par catégorie
final articlesByCategorieProvider =
    FutureProvider.family<List<ArticleList>, int>((ref, idCategorie) async {
      return await articleService.getArticlesByCategory(idCategorie);
    });
