import 'package:flutter/material.dart';

class ArticleDetailScreen extends StatelessWidget {
  final int id;
  final String title;
  final String author;
  final String date;
  final String content;

  const ArticleDetailScreen({
    super.key,
    required this.id,
    required this.title,
    required this.author,
    required this.date,
    required this.content,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(title)),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: Theme.of(context).textTheme.headlineMedium),
            const SizedBox(height: 10),
            Text('Auteur : $author'),
            Text('Date : $date'),
            const SizedBox(height: 20),
            Text(content),
          ],
        ),
      ),
    );
  }
}
