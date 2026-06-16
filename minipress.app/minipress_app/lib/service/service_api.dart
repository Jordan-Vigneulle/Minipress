import 'package:dio/dio.dart';
import 'article_service.dart';
import 'categorie_service.dart';
import 'utilisateur_service.dart';

final _dio = Dio(
  BaseOptions(
    baseUrl: 'http://docketu.iutnc.univ-lorraine.fr:29029/api',
    headers: {'Content-Type': 'application/json'},
  ),
);

final articleService = ArticleService(_dio);
final categorieService = CategorieService(_dio);
final utilisateurService = UtilisateurService(_dio);
