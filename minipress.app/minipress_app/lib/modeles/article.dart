import 'categorie.dart';
import 'utilisateur.dart';

class Article {
  final int id;
  final String titre;
  final String resume;
  final String contenu;
  final String date;
  final int idCategorie;
  final int idUtilisateur;
  final bool estPublie;
  final Categorie? categorie;
  final Utilisateur? utilisateur;

  const Article({
    required this.id,
    required this.titre,
    required this.resume,
    required this.contenu,
    required this.date,
    required this.idCategorie,
    required this.idUtilisateur,
    required this.estPublie,
    this.categorie,
    this.utilisateur,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'] as int,
      titre: json['titre'] as String,
      resume: json['resume'] as String,
      contenu: json['contenu'] as String,
      date: json['date'] as String,
      idCategorie: json['id_categorie'] as int,
      idUtilisateur: json['id_utilisateur'] as int,
      estPublie: json['est_publie'] as bool,
      categorie: json['categorie'] != null
          ? Categorie.fromJson(json['categorie'])
          : null,
      utilisateur: json['utilisateur'] != null
          ? Utilisateur.fromJson(json['utilisateur'])
          : null,
    );
  }
}
