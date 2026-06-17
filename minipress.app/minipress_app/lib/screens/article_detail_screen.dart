import 'package:flutter/material.dart';
import '../modeles/articleDetail.dart';
import '../service/service_api.dart';
import 'package:flutter_markdown/flutter_markdown.dart';

class ArticleDetailScreen extends StatefulWidget {
  final String uri;

  const ArticleDetailScreen({super.key, required this.uri});

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  late Future<ArticleDetail> _articleFuture;

  @override
  void initState() {
    super.initState();
    // Fetch detail API via URI
    _articleFuture = articleService.getArticleByUri(widget.uri);
  }

  void _refresh() {
    setState(() {
      _articleFuture = articleService.getArticleByUri(widget.uri);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Détail de l\'article')),
      // FutureBuilder pour charger l'article depuis l'API
      body: FutureBuilder<ArticleDetail>(
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
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Theme.of(context).colorScheme.primary,
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
                if (article.images.isNotEmpty) ...[
                  const SizedBox(height: 16),
                  const Divider(),
                  const Text(
                    'Images',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                  const SizedBox(height: 8),
                  ...article.images.map(
                    (img) => Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: Image.network(
                          'http://docketu.iutnc.univ-lorraine.fr:29029${img.url}',
                          fit: BoxFit.cover,
                          loadingBuilder: (context, child, loadingProgress) {
                            if (loadingProgress == null) return child;
                            return const Center(
                              child: CircularProgressIndicator(),
                            );
                          },
                          errorBuilder: (context, error, _) => Container(
                            height: 100,
                            color: Colors.grey[200],
                            child: const Center(
                              child: Icon(
                                Icons.broken_image,
                                color: Colors.grey,
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
                MarkdownBody(
                  data: article.resume,
                  styleSheet: MarkdownStyleSheet(
                    p: TextStyle(fontSize: 16, fontStyle: FontStyle.italic),
                  ),
                ),
                const SizedBox(height: 16),
                MarkdownBody(
                  data: article.contenu ?? '',
                  styleSheet: MarkdownStyleSheet(p: TextStyle(fontSize: 16)),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
