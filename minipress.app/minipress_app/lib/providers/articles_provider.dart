import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../modeles/article.dart';
import '../service/service_api.dart';

final articlesProvider = FutureProvider<List<Article>>((ref) async {
  return await articleService.getArticles();
});

final articleDetailProvider = FutureProvider.family<Article, int>((
  ref,
  id,
) async {
  return await articleService.getArticleById(id);
});

final articlesByCategorieProvider = FutureProvider.family<List<Article>, int>((
  ref,
  idCategorie,
) async {
  return await articleService.getArticlesByCategory(idCategorie);
});
