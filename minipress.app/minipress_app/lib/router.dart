import 'package:go_router/go_router.dart';
import 'screens/articles_list_screen.dart';
import 'screens/article_detail_screen.dart';

final router = GoRouter(
  initialLocation: '/articles',
  routes: [
    GoRoute(
      path: '/articles',
      builder: (context, state) => const ArticlesListScreen(),
      routes: [
        GoRoute(
          path: ':id',
          builder: (context, state) {
            final id = int.parse(state.pathParameters['id']!);
            // Remplacer les données de test.
            return ArticleDetailScreen(
              id: id,
              title: 'Article #$id',
              author: 'Auteur TastyCrousty',
              date: '2026-06-12',
              content:
                  'Contenu de l\'article #$id. Bla bla bla, bleh bleh bleh, blou blou blou... KA-CHOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOW',
            );
          },
        ),
      ],
    ),
  ],
);
