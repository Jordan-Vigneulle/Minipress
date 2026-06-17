import 'utilisateur.dart';

class ArticleList {
  final int id;
  final String titre;
  final String resume;
  final String date;
  final int idUtilisateur;
  final String uri;
  final Utilisateur? utilisateur;

  const ArticleList({
    required this.id,
    required this.titre,
    required this.resume,
    required this.date,
    required this.idUtilisateur,
    required this.uri,
    this.utilisateur,
  });

  factory ArticleList.fromJson(Map<String, dynamic> json) {
    return ArticleList(
      id: json['id'] ?? 0,
      titre: json['titre']?.toString() ?? '',
      resume: json['resume']?.toString() ?? '',
      date: json['date']?.toString() ?? '',
      idUtilisateur: json['id_utilisateur'] ?? 0,
      uri: json['uri']?.toString() ?? '',
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
