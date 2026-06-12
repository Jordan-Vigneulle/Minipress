class Utilisateur {
  final int id;
  final String pseudo;

  const Utilisateur({required this.id, required this.pseudo});

  factory Utilisateur.fromJson(Map<String, dynamic> json) {
    return Utilisateur(id: json['id'] as int, pseudo: json['pseudo'] as String);
  }
}
