class ImageArticle {
  final int id;
  final String url;

  const ImageArticle({required this.id, required this.url});

  factory ImageArticle.fromJson(Map<String, dynamic> json) {
    return ImageArticle(id: json['id'] as int, url: json['url'] as String);
  }
}
