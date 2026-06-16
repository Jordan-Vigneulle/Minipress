import 'package:flutter/material.dart';
import '../modeles/article.dart';
import '../service/service_api.dart';

class ArticleDetailScreen extends StatefulWidget {
  final int id;

  const ArticleDetailScreen({super.key, required this.id});

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  late Future<Article> _articleFuture;

  @override
  void initState() {
    super.initState();
    // Fetch detail API
    _articleFuture = articleService.getArticleById(widget.id);
  }

  void _refresh() {
    setState(() {
      _articleFuture = articleService.getArticleById(widget.id);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Détail de l\'article')),
      // FutureBuilder pour charger l'article depuis l'API
      body: FutureBuilder<Article>(
        future: _articleFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            // Icone de chargement en attendant la fin des requêtes
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Erreur : ${snapshot.error}'),
                  const SizedBox(height: 10),
                  ElevatedButton(
                    onPressed: _refresh,
                    child: const Text('Réessayer'),
                  ),
                ],
              ),
            );
          }
          if (!snapshot.hasData) {
            return const Center(child: Text('Aucune donnée trouvée.'));
          }

          final article = snapshot.data!;
          // On tire vers le bas pour rafraîchir l'affichage
          return RefreshIndicator(
            onRefresh: () async => _refresh(),
            // Liste verticale pour afficher tout les articles
            child: ListView(
              padding: const EdgeInsets.all(16.0),
              physics: const AlwaysScrollableScrollPhysics(),
              children: [
                Text(
                  article.titre,
                  style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                // Pour afficher la date et l'auteur sur une même ligne
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Par ${article.utilisateur?.pseudo ?? 'Auteur #${article.idUtilisateur}'}',
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Colors.blueAccent,
                      ),
                    ),
                    Text(
                      article.formattedDate,
                      style: const TextStyle(color: Colors.grey),
                    ),
                  ],
                ),
                // Ligne de séparation (entre l'entête et le contenu de l'article)
                const Divider(height: 24),
                Text(
                  article.resume,
                  style: const TextStyle(
                    fontSize: 16,
                    fontStyle: FontStyle.italic,
                  ),
                ),
                const SizedBox(height: 16),
                Text(
                  article.contenu,
                  style: const TextStyle(fontSize: 16, height: 1.4),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
