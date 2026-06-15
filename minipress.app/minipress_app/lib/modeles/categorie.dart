class Categorie {
  final int id;
  final String titre;

  const Categorie({required this.id, required this.titre});

  factory Categorie.fromJson(Map<String, dynamic> json) {
    return Categorie(id: json['id'] as int, titre: json['titre'] as String);
  }
}
