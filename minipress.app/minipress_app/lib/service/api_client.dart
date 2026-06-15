import 'package:dio/dio.dart';
import '../modeles/article.dart';
import '../modeles/categorie.dart';

class ApiClient {
  static const String _baseUrl =
      'http://docketu.iutnc.univ-lorraine.fr:29029/api';

  final Dio _dio = Dio(
    BaseOptions(
      baseUrl: _baseUrl,
      headers: {'Content-Type': 'application/json'},
    ),
  );

  // Tous les articles
  Future<List<Article>> getArticles() async {
    final response = await _dio.get('/articles');
    return (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }

  // Un article par id
  Future<Article> getArticleById(int id) async {
    final response = await _dio.get('/articles/$id');
    return Article.fromJson(response.data);
  }

  // Articles d'une catégorie
  Future<List<Article>> getArticlesByCategory(int idCategorie) async {
    final response = await _dio.get('/categories/$idCategorie/articles');
    return (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }

  // Articles d'un auteur
  Future<List<Article>> getArticlesByAuthor(int idUtilisateur) async {
    final response = await _dio.get('/auteurs/$idUtilisateur/articles');
    return (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
  }

  Future<List<Categorie>> getCategories() async {
    final response = await _dio.get('/categories');
    return (response.data as List)
        .map((json) => Categorie.fromJson(json))
        .toList();
  }
}
