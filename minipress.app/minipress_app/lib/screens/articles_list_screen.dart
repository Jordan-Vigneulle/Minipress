import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../widgets/article_tile.dart';

class ArticlesListScreen extends StatelessWidget {
  const ArticlesListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('MiniPress - Articles')),
      body: ListView.builder(
        itemCount: 5,
        itemBuilder: (context, index) {
          final id = index + 1;
          return ArticleTile(
            id: id,
            title: 'Article test #$id',
            author: 'Auteur TastyCrousty',
            date: '2026-06-12',
            onTap: () => context.go('/articles/$id'),
          );
        },
      ),
    );
  }
}
