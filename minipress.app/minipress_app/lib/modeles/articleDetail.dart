import 'categorie.dart';
import 'utilisateur.dart';
import 'ImageArticle.dart';

class ArticleDetail {
  final int id;
  final String titre;
  final String resume;
  final String? contenu;
  final String date;
  final int idCategorie;
  final int idUtilisateur;
  final bool estPublie;
  final Categorie? categorie;
  final List<ImageArticle> images;
  final Utilisateur? utilisateur;

  const ArticleDetail({
    required this.id,
    required this.titre,
    required this.resume,
    this.contenu,
    required this.date,
    required this.idCategorie,
    required this.idUtilisateur,
    required this.estPublie,
    this.categorie,
    required this.images,
    this.utilisateur,
  });

  factory ArticleDetail.fromJson(Map<String, dynamic> json) {
    return ArticleDetail(
      id: json['id'] ?? 0,
      titre: json['titre']?.toString() ?? '',
      resume: json['resume']?.toString() ?? '',
      contenu: json['contenu']?.toString(),
      date: json['date']?.toString() ?? '',
      idCategorie: json['id_categorie'] ?? 0,
      idUtilisateur: json['id_utilisateur'] ?? 0,
      estPublie: json['est_publie'] == true,
      categorie: json['categorie'] != null
          ? Categorie.fromJson(json['categorie'])
          : null,
      images: (json['images'] as List? ?? [])
          .map((img) => ImageArticle.fromJson(img))
          .toList(),
      utilisateur: json['utilisateur'] != null
          ? Utilisateur.fromJson(json['utilisateur'])
          : null,
    );
  }

  String get formattedDate {
    try {
      final dateTime = DateTime.parse(date);
      final day = dateTime.day.toString().padLeft(2, '0');
      final month = dateTime.month.toString().padLeft(2, '0');
      final year = dateTime.year;
      return '$day/$month/$year';
    } catch (_) {
      return date;
    }
  }
}
