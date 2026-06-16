import 'package:flutter/material.dart';
import 'package:flutter_markdown/flutter_markdown.dart';
import '../modeles/articleList.dart';

class ArticleTile extends StatelessWidget {
  final ArticleList article;
  final VoidCallback onTap;
  final VoidCallback onAuthorTap;
  final Color? backgroundColor;

  const ArticleTile({
    super.key,
    required this.article,
    required this.onTap,
    required this.onAuthorTap,
    this.backgroundColor,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      color: backgroundColor,
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      elevation: 2,
      // Rend la zone cliquable avec un effet visuel quand clique dessus
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(
          24,
        ), // matches radius-lg of card border radius
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Titre de l'article
              MarkdownBody(
                data: article.titre,
                styleSheet: MarkdownStyleSheet(
                  p: Theme.of(context).textTheme.titleMedium!.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const SizedBox(height: 8),
              // Résumé de l'article
              MarkdownBody(
                data: article.resume,
                styleSheet: MarkdownStyleSheet(
                  p: Theme.of(context).textTheme.bodyMedium,
                ),
                shrinkWrap: true,
              ),
              const SizedBox(height: 12),
              // Alignement horizontal entre l'auteur et la date
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  // Auteur de l'article cliquable
                  InkWell(
                    onTap: onAuthorTap,
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 4),
                      child: Text(
                        'Par ${article.utilisateur?.pseudo ?? 'Auteur #${article.idUtilisateur}'}',
                        style: TextStyle(
                          color: Theme.of(context).colorScheme.primary,
                          fontWeight: FontWeight.bold,
                          decoration: TextDecoration.underline,
                        ),
                      ),
                    ),
                  ),
                  // Date de création
                  Text(
                    article.formattedDate,
                    style: const TextStyle(color: Colors.grey, fontSize: 12),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
