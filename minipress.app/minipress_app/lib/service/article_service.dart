import 'package:dio/dio.dart';

import '../modeles/articleList.dart';
import '../modeles/articleDetail.dart';

class ArticleService {
  final Dio _dio;
  ArticleService(this._dio);

  // LISTE ARTICLES
  Future<List<ArticleList>> getArticles() async {
    final response = await _dio.get('/articles');

    return (response.data as List)
        .map((json) => ArticleList.fromJson(json))
        .toList();
  }

  // ARTICLE DÉTAIL
  Future<ArticleDetail> getArticleById(int id) async {
    final response = await _dio.get('/articles/$id');

    return ArticleDetail.fromJson(response.data);
  }

  // PAR CATÉGORIE
  Future<List<ArticleList>> getArticlesByCategory(int idCategorie) async {
    final response = await _dio.get('/categories/$idCategorie/articles');

    if (response.data is Map) {
      final data = response.data as Map<String, dynamic>;
      return (data['articles'] as List)
          .map((json) => ArticleList.fromJson(json))
          .toList();
    }

    // Si l'API retourne directement une liste
    return (response.data as List)
        .map((json) => ArticleList.fromJson(json))
        .toList();
  }

  // PAR AUTEUR
  Future<List<ArticleList>> getArticlesByAuthor(int idUtilisateur) async {
    final response = await _dio.get('/articles');

    final data = (response.data as List)
        .map((json) => ArticleList.fromJson(json))
        .toList();

    return data.where((a) => a.idUtilisateur == idUtilisateur).toList();
  }
}
