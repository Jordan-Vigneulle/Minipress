class Auteur {
  final int id;
  final String pseudo;

  const Auteur({required this.id, required this.pseudo});

  factory Auteur.fromJson(Map<String, dynamic> json) {
    return Auteur(id: json['id'] as int, pseudo: json['pseudo'] as String);
  }
}
