import 'package:dio/dio.dart';
import '../modeles/article.dart';

class ArticleService {
  final Dio _dio;
  ArticleService(this._dio);

  Future<List<Article>> getArticles() async {
    final response = await _dio.get('/articles');
    return (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }

  Future<Article> getArticleById(int id) async {
    final response = await _dio.get('/articles/$id');
    return Article.fromJson(response.data);
  }

  Future<List<Article>> getArticlesByCategory(int idCategorie) async {
    final response = await _dio.get('/categories/$idCategorie/articles');
    return (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }

  Future<List<Article>> getArticlesByAuthor(int idUtilisateur) async {
    final response = await _dio.get('/auteurs/$idUtilisateur');
    final data = response.data as Map<String, dynamic>;
    return (data['articles'] as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }
}
