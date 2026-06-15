import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import './service/api_client.dart';

import 'router.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  final client = ApiClient();

  try {
    final articles = await client.getArticles();
    print('${articles.length} articles récupérés');
    print('Premier article : ${articles.first.titre}');
    print('Auteur (null normal) : ${articles.first.utilisateur?.pseudo}');

    // Test du détail avec l'id du premier article
    final detail = await client.getArticleById(articles.first.id);
    print('--- DÉTAIL ---');
    print('Titre : ${detail.titre}');
    print('Auteur : ${detail.utilisateur?.pseudo}');
    print('Catégorie : ${detail.categorie?.titre}');
  } catch (e) {
    print('Erreur : $e');
  }

  runApp(
    // Requis pour Riverpod
    const ProviderScope(child: MyApp()),
  );

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'Minipress',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.blueAccent),
        useMaterial3: true,
      ),
      routerConfig: router,
    );
  }
}
