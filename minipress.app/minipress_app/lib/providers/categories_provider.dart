import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../modeles/categorie.dart';
import '../service/service_api.dart';

final categoriesProvider = FutureProvider<List<Categorie>>((ref) async {
  return await categorieService.getCategories();
});
