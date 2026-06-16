import 'package:dio/dio.dart';
import '../modeles/categorie.dart';

class CategorieService {
  final Dio _dio;
  CategorieService(this._dio);

  Future<List<Categorie>> getCategories() async {
    final response = await _dio.get('/categories');
    return (response.data as List)
        .map((json) => Categorie.fromJson(json))
        .toList();
  }
}
