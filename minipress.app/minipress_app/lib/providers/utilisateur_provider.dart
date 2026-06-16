import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../modeles/utilisateur.dart';
import '../modeles/articleList.dart';
import '../service/service_api.dart';

// Liste auteurs
final utilisateurProvider = FutureProvider<List<Utilisateur>>((ref) async {
  return await utilisateurService.getAuteurs();
});

// Auteur par ID
final utilisateurByIdProvider = FutureProvider.family<Utilisateur, int>((
  ref,
  id,
) async {
  return await utilisateurService.getAuteurById(id);
});

// Articles d’un auteur
final articlesByAuteurProvider = FutureProvider.family<List<ArticleList>, int>((
  ref,
  id,
) async {
  return await utilisateurService.getArticlesByAuteur(id);
});
