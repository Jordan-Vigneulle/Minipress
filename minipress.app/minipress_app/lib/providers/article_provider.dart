import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../modeles/article.dart';
import '../service/api_client.dart';

final articlesProvider = FutureProvider<List<Article>>((ref) async {
  return await ApiClient().getArticles();
});

final articleDetailProvider = FutureProvider.family<Article, int>((
  ref,
  id,
) async {
  return await ApiClient().getArticleById(id);
});

final articlesByCategorieProvider = FutureProvider.family<List<Article>, int>((
  ref,
  idCategorie,
) async {
  return await ApiClient().getArticlesByCategory(idCategorie);
});

final articlesByAuteurProvider = FutureProvider.family<List<Article>, int>((
  ref,
  idUtilisateur,
) async {
  return await ApiClient().getArticlesByAuthor(idUtilisateur);
});
