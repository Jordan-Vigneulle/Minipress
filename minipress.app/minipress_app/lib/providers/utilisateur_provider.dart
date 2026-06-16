import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../modeles/utilisateur.dart';
import '../service/service_api.dart';
import '../modeles/article.dart';

// Liste de tous les auteurs
final utilisateurProvider = FutureProvider<List<Utilisateur>>((ref) async {
  return await utilisateurService.getAuteurs();
});

// Un auteur par id
final utilisateurByIdProvider = FutureProvider.family<Utilisateur, int>((
  ref,
  id,
) async {
  return await utilisateurService.getAuteurById(id);
});

// Articles d'un auteur
final articlesByAuteurProvider = FutureProvider.family<List<Article>, int>((
  ref,
  id,
) async {
  return await utilisateurService.getArticlesByAuteur(id);
});
