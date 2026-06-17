import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/articles_provider.dart';
import 'package:flutter_markdown/flutter_markdown.dart';

class ArticleDetailScreen extends ConsumerWidget {
  final String uri;

  const ArticleDetailScreen({super.key, required this.uri});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // On écoute le provider en lui passant l'URI de l'article
    final articleAsync = ref.watch(articleDetailProvider(uri));

    return Scaffold(
      appBar: AppBar(title: const Text('Détail de l\'article')),
      body: articleAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (error, stackTrace) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text('Erreur : $error'),
              const SizedBox(height: 10),
              ElevatedButton(
                onPressed: () => ref.refresh(articleDetailProvider(uri)),
                child: const Text('Réessayer'),
              ),
            ],
          ),
        ),
        data: (article) {
          return RefreshIndicator(
            onRefresh: () async {
              ref.refresh(articleDetailProvider(uri));
            },
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
