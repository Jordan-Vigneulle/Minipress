import 'package:flutter/material.dart';

class ArticleDetailScreen extends StatelessWidget {
  final int id;

  const ArticleDetailScreen({super.key, required this.id});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Article #$id')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Titre de l\'article #$id',
              style: Theme.of(context).textTheme.headlineMedium,
            ),
            const SizedBox(height: 10),
            const Text('Auteur : Auteur TastyCrousty'),
            const Text('Date : 2026-06-12'),
            const SizedBox(height: 20),
            const Text(
              'Contenu de l\'article (mock). Bla bla bla, bleh bleh bleh, blou blou blou... En attente des données réelles de l\'API.',
            ),
          ],
        ),
      ),
    );
  }
}
