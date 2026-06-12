import 'package:flutter/material.dart';

class ArticleTile extends StatelessWidget {
  final int id;
  final String title;
  final String author;
  final String date;
  final VoidCallback onTap;

  const ArticleTile({
    super.key,
    required this.id,
    required this.title,
    required this.author,
    required this.date,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ListTile(
        title: Text(title),
        subtitle: Text('Par $author le $date'),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}
