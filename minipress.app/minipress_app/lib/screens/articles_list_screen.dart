import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:minipress_app/main.dart';
import '../modeles/articleList.dart';
import '../modeles/categorie.dart';
import '../modeles/articleSort.dart';
import '../widgets/article_tile.dart';
import '../providers/articles_provider.dart';
import '../providers/categories_provider.dart';
import '../providers/utilisateur_provider.dart';

class ArticlesListScreen extends ConsumerStatefulWidget {
  const ArticlesListScreen({super.key});

  @override
  ConsumerState<ArticlesListScreen> createState() => _ArticlesListScreenState();
}

class _ArticlesListScreenState extends ConsumerState<ArticlesListScreen> {
  final TextEditingController _searchController = TextEditingController();

  // États locaux de l'interface (UI)
  String _searchQuery = '';
  ArticleSort _sort = ArticleSort.dateDesc;
  int? _selectedCategory;
  int? _selectedAuthor;
  String? _selectedAuthorName;

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  // Fonction centrale pour rafraîchir les données réseau via Riverpod
  Future<void> _refreshAllData() async {
    ref.invalidate(categoriesProvider);
    if (_selectedCategory != null) {
      ref.invalidate(articlesByCategorieProvider(_selectedCategory!));
    } else if (_selectedAuthor != null) {
      ref.invalidate(articlesByAuteurProvider(_selectedAuthor!));
    } else {
      ref.invalidate(articlesProvider(_sort));
    }
  }

  // Réinitialise tous les filtres pour revenir à l'état de base
  void _resetFilters() {
    setState(() {
      _selectedCategory = null;
      _selectedAuthor = null;
      _selectedAuthorName = null;
      _searchQuery = '';
      _searchController.clear();
    });
  }

  @override
  Widget build(BuildContext context) {
    final categoriesAsync = ref.watch(categoriesProvider);

    final AsyncValue<List<ArticleList>> articlesAsync =
        _selectedCategory != null
        ? ref.watch(articlesByCategorieProvider(_selectedCategory!))
        : _selectedAuthor != null
        ? ref.watch(articlesByAuteurProvider(_selectedAuthor!))
        : ref.watch(articlesProvider(_sort));

    return Scaffold(
      appBar: AppBar(
        title: const Text('MiniPress'),
        actions: [
          PopupMenuButton<ArticleSort>(
            icon: const Icon(Icons.sort),
            tooltip: 'Changer le tri',
            onSelected: (value) => setState(() => _sort = value),
            itemBuilder: (context) => [
              CheckedPopupMenuItem(
                value: ArticleSort.dateDesc,
                checked: _sort == ArticleSort.dateDesc,
                child: const Text('Plus récents d\'abord'),
              ),
              CheckedPopupMenuItem(
                value: ArticleSort.dateAsc,
                checked: _sort == ArticleSort.dateAsc,
                child: const Text('Plus anciens d\'abord'),
              ),
              CheckedPopupMenuItem(
                value: ArticleSort.auteur,
                checked: _sort == ArticleSort.auteur,
                child: const Text('Par auteur'),
              ),
            ],
          ),
          IconButton(
            icon: const Icon(Icons.brightness_6),
            tooltip: 'Changer le thème',
            onPressed: () {
              final isDark = Theme.of(context).brightness == Brightness.dark;
              themeNotifier.value = isDark ? ThemeMode.light : ThemeMode.dark;
            },
          ),
        ],
      ),

      drawer: Drawer(
        child: categoriesAsync.when(
          loading: () => const Center(child: CircularProgressIndicator()),
          error: (err, _) => Center(child: Text('Erreur catégories : $err')),
          data: (categories) => ListView(
            padding: EdgeInsets.zero,
            children: [
              const DrawerHeader(
                decoration: BoxDecoration(color: Color(0xFF5B4CC4)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    Text(
                      'MiniPress',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      "L'actu pour tous",
                      style: TextStyle(color: Colors.white, fontSize: 18),
                    ),
                    SizedBox(height: 10),
                    Text(
                      'Navigation',
                      style: TextStyle(color: Colors.white70, fontSize: 14),
                    ),
                  ],
                ),
              ),
              ListTile(
                leading: const Icon(Icons.article_outlined),
                title: const Text('Tous les articles'),
                selected: _selectedCategory == null && _selectedAuthor == null,
                onTap: () {
                  Navigator.of(context).pop();
                  _resetFilters();
                },
              ),
              const Divider(),
              const Padding(
                padding: EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                child: Text(
                  'CATÉGORIES',
                  style: TextStyle(fontSize: 12, color: Colors.grey),
                ),
              ),
              ...categories.map(
                (cat) => ListTile(
                  leading: const Icon(Icons.label_outline),
                  title: Text(cat.titre),
                  selected: _selectedCategory == cat.id,
                  onTap: () {
                    setState(() {
                      _selectedCategory = cat.id;
                      _selectedAuthor = null;
                      _selectedAuthorName = null;
                    });
                    Navigator.of(context).pop();
                  },
                ),
              ),
              const Divider(),
              ListTile(
                leading: const Icon(Icons.people_outline),
                title: const Text('Auteurs'),
                onTap: () async {
                  Navigator.of(context).pop();
                  final result = await context.push<Map<String, dynamic>>(
                    '/auteurs',
                  );
                  if (result != null) {
                    setState(() {
                      _selectedAuthor = result['id'] as int;
                      _selectedAuthorName = result['nom'] as String;
                      _selectedCategory = null;
                    });
                  }
                },
              ),
            ],
          ),
        ),
      ),

      body: Column(
        children: [
          if (_selectedCategory != null || _selectedAuthor != null)
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 0),
              child: Row(
                children: [
                  if (_selectedCategory != null)
                    categoriesAsync.maybeWhen(
                      data: (list) {
                        final currentCat = list.firstWhere(
                          (c) => c.id == _selectedCategory,
                          orElse: () => Categorie(id: -1, titre: ''),
                        );
                        return InputChip(
                          label: Text('Catégorie : ${currentCat.titre}'),
                          onDeleted: _resetFilters,
                        );
                      },
                      orElse: () => const SizedBox.shrink(),
                    ),
                  if (_selectedAuthor != null)
                    InputChip(
                      label: Text(
                        'Auteur : ${_selectedAuthorName ?? "ID $_selectedAuthor"}',
                      ),
                      onDeleted: _resetFilters,
                    ),
                ],
              ),
            ),

          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(
              controller: _searchController,
              onChanged: (value) => setState(() => _searchQuery = value),
              decoration: InputDecoration(
                hintText: 'Rechercher par titre ou résumé...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchQuery.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          setState(() => _searchQuery = '');
                        },
                      )
                    : null,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
          ),

          Expanded(
            child: articlesAsync.when(
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (err, _) => Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.wifi_off, size: 48, color: Colors.grey),
                    const SizedBox(height: 16),
                    const Text(
                      'Impossible de contacter le serveur.',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      err.toString(),
                      style: const TextStyle(color: Colors.grey, fontSize: 12),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 10),
                    ElevatedButton(
                      onPressed: _refreshAllData,
                      child: const Text('Réessayer'),
                    ),
                  ],
                ),
              ),
              data: (allArticles) {
                // Filtrage texte en mémoire ; le tri vient désormais du serveur
                Iterable<ArticleList> filteredList = allArticles;
                if (_searchQuery.isNotEmpty) {
                  final query = _searchQuery.toLowerCase();
                  filteredList = filteredList.where(
                    (a) =>
                        a.titre.toLowerCase().contains(query) ||
                        a.resume.toLowerCase().contains(query),
                  );
                }

                final displayedArticles = filteredList.toList();

                if (displayedArticles.isEmpty) {
                  return const Center(child: Text('Aucun article trouvé.'));
                }

                return RefreshIndicator(
                  onRefresh: _refreshAllData,
                  child: ListView.builder(
                    physics: const AlwaysScrollableScrollPhysics(),
                    itemCount: displayedArticles.length,
                    itemBuilder: (context, index) {
                      final article = displayedArticles[index];
                      return ArticleTile(
                        article: article,
                        onTap: () =>
                            context.go('/articles/detail?uri=${article.uri}'),
                        onAuthorTap: () {
                          setState(() {
                            _selectedAuthor = article.idUtilisateur;
                            _selectedAuthorName = article.utilisateur?.pseudo;
                            _selectedCategory = null;
                          });
                        },
                      );
                    },
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
