import 'package:dio/dio.dart';

import '../modeles/utilisateur.dart';
import '../modeles/articleList.dart';

class UtilisateurService {
  final Dio _dio;
  UtilisateurService(this._dio);

  // LISTE AUTEURS
  Future<List<Utilisateur>> getAuteurs() async {
    final response = await _dio.get('/auteurs');

    return (response.data as List)
        .map((json) => Utilisateur.fromJson(json))
        .toList();
  }

  // AUTEUR PAR ID
  Future<Utilisateur> getAuteurById(int id) async {
    final response = await _dio.get('/auteurs');

    final liste = (response.data as List)
        .map((json) => Utilisateur.fromJson(json))
        .toList();

    return liste.firstWhere((a) => a.id == id);
  }

  // ARTICLES D’UN AUTEUR
  Future<List<ArticleList>> getArticlesByAuteur(int idUtilisateur) async {
    final response = await _dio.get('/auteurs/$idUtilisateur/articles');
    final data = response.data as Map<String, dynamic>;

    final auteur = Utilisateur.fromJson(data['auteur']);

    return (data['articles'] as List).map((json) {
      // On injecte l'auteur dans chaque article
      final articleJson = Map<String, dynamic>.from(json);
      articleJson['id_utilisateur'] = auteur.id;
      articleJson['utilisateur'] = {'id': auteur.id, 'pseudo': auteur.pseudo};
      return ArticleList.fromJson(articleJson);
    }).toList();
  }
}
