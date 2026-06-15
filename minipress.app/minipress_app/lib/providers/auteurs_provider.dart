import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../modeles/auteur.dart';
import '../service/service_api.dart';
import '../modeles/article.dart';

// Liste de tous les auteurs
final auteursProvider = FutureProvider<List<Auteur>>((ref) async {
  return await auteurService.getAuteurs();
});

// Un auteur par id
final auteurByIdProvider = FutureProvider.family<Auteur, int>((ref, id) async {
  return await auteurService.getAuteurById(id);
});

// Articles d'un auteur
final articlesByAuteurProvider = FutureProvider.family<List<Article>, int>((
  ref,
  id,
) async {
  return await auteurService.getArticlesByAuteur(id);
});
