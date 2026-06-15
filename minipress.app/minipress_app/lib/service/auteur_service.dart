import 'package:dio/dio.dart';
import '../modeles/auteur.dart';
import '../modeles/article.dart';

class AuteurService {
  final Dio _dio;
  AuteurService(this._dio);

  Future<List<Auteur>> getAuteurs() async {
    final response = await _dio.get('/auteurs');
    return (response.data as List)
        .map((json) => Auteur.fromJson(json))
        .toList();
  }

  Future<Auteur> getAuteurById(int id) async {
    final response = await _dio.get('/auteurs');
    final liste = (response.data as List)
        .map((json) => Auteur.fromJson(json))
        .toList();
    return liste.firstWhere((a) => a.id == id);
  }

  Future<List<Article>> getArticlesByAuteur(int idUtilisateur) async {
    final response = await _dio.get('/articles');
    final tous = (response.data as List)
        .map((json) => Article.fromJson(json))
        .toList();
    return tous.where((a) => a.idUtilisateur == idUtilisateur).toList();
  }
}
