import 'package:go_router/go_router.dart';
import 'screens/articles_list_screen.dart';
import 'screens/article_detail_screen.dart';
import 'screens/auteurs_list_screen.dart';

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
            return ArticleDetailScreen(id: id);
          },
        ),
      ],
    ),
    GoRoute(
      path: '/auteurs',
      builder: (context, state) => const AuteursListScreen(),
    ),
  ],
);
