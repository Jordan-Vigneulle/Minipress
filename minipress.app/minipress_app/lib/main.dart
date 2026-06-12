import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import './service/api_client.dart';

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

  runApp(const ProviderScope(child: MyApp()));
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Minipress',
      theme: ThemeData(colorScheme: .fromSeed(seedColor: Colors.deepPurple)),
      home: const MyHomePage(title: 'Minipress'),
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  int _counter = 0;

  void _incrementCounter() {
    setState(() {
      _counter++;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: Text(widget.title),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: .center,
          children: [
            const Text('Vous avez appuyer le boutton :'),
            Text(
              '$_counter',
              style: Theme.of(context).textTheme.headlineMedium,
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _incrementCounter,
        tooltip: 'Increment',
        child: const Icon(Icons.add),
      ),
    );
  }
}
