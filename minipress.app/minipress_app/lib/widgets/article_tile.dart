import 'package:flutter/material.dart';
import '../modeles/article.dart';

class ArticleTile extends StatelessWidget {
  final Article article;
  final VoidCallback onTap;
  final VoidCallback onAuthorTap;

  const ArticleTile({
    super.key,
    required this.article,
    required this.onTap,
    required this.onAuthorTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      elevation: 2,
      // Rend la zone cliquable avec un effet visuel quand clique dessus
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(8),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Titre de l'article
              Text(
                article.titre,
                style: Theme.of(
                  context,
                ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              // Résumé de l'article
              Text(
                article.resume,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: TextStyle(color: Colors.grey[600], fontSize: 14),
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
                        style: const TextStyle(
                          color: Colors.blueAccent,
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
