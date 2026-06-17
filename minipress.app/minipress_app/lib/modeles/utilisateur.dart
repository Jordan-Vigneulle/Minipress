class Utilisateur {
  final int id;
  final String pseudo;
  final String cheminAccesImg;

  const Utilisateur({
    required this.id,
    required this.pseudo,
    required this.cheminAccesImg,
  });

  factory Utilisateur.fromJson(Map<String, dynamic> json) {
    return Utilisateur(
      id: json['id'] as int,
      pseudo: json['pseudo'] as String,
      cheminAccesImg: json['chemin_acces_img'].toString(),
    );
  }
}
