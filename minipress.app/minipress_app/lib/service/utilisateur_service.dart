import 'package:dio/dio.dart';
import '../modeles/utilisateur.dart';
import '../modeles/article.dart';

class UtilisateurService {
  final Dio _dio;
  UtilisateurService(this._dio);

  Future<List<Utilisateur>> getAuteurs() async {
    final response = await _dio.get('/auteurs');
    return (response.data as List)
        .map((json) => Utilisateur.fromJson(json))
        .toList();
  }

  Future<Utilisateur> getAuteurById(int id) async {
    final response = await _dio.get('/auteurs');
    final liste = (response.data as List)
        .map((json) => Utilisateur.fromJson(json))
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
