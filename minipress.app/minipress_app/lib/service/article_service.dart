import 'package:dio/dio.dart';

import '../modeles/articleList.dart';
import '../modeles/articleDetail.dart';

class ArticleService {
  final Dio _dio;
  ArticleService(this._dio);

  // LISTE ARTICLES
  Future<List<ArticleList>> getArticles({String? sort}) async {
    final response = await _dio.get(
      '/articles',
      queryParameters: sort != null ? {'sort': sort} : null,
    );
    return (response.data as List)
        .map((json) => ArticleList.fromJson(json))
        .toList();
  }

  // ARTICLE DÉTAIL PAR URI
  Future<ArticleDetail> getArticleByUri(String uri) async {
    final path = uri.startsWith('/api') ? uri.replaceFirst('/api', '') : uri;
    final response = await _dio.get(path);

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
}
