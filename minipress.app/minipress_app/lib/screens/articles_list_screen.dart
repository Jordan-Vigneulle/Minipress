import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:minipress_app/main.dart';
import '../modeles/articleList.dart';
import '../modeles/categorie.dart';
import '../service/service_api.dart';
import '../widgets/article_tile.dart';

class ArticlesListScreen extends StatefulWidget {
  const ArticlesListScreen({super.key});

  @override
  State<ArticlesListScreen> createState() => _ArticlesListScreenState();
}

class _ArticlesListScreenState extends State<ArticlesListScreen> {
  final TextEditingController _searchController = TextEditingController();

  List<ArticleList> _allArticles = [];
  List<ArticleList> _filteredArticles = [];
  List<Categorie> _categories = [];

  bool _isLoading = true;
  String _errorMessage = '';

  String _searchQuery = '';
  bool _sortDescending = true;
  int? _selectedCategory;
  int? _selectedAuthor;
  String? _selectedAuthorName;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _loadData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
      _selectedCategory = null;
      _selectedAuthor = null;
      _selectedAuthorName = null;
      _searchQuery = '';
      _searchController.clear();
    });

    try {
      final results = await Future.wait([
        articleService.getArticles(),
        categorieService.getCategories(),
      ]);

      _allArticles = results[0] as List<ArticleList>;
      _categories = results[1] as List<Categorie>;

      _applyFiltersAndSort();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  Future<void> _loadByCategory(int idCategorie) async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final articles = await articleService.getArticlesByCategory(idCategorie);
      _allArticles = articles;
      _applyFiltersAndSort();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  Future<void> _loadByAuteur(int idAuteur) async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final articles = await utilisateurService.getArticlesByAuteur(idAuteur);
      _allArticles = articles;
      _applyFiltersAndSort();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  void _applyFiltersAndSort() {
    Iterable<ArticleList> list = _allArticles;

    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      list = list.where(
        (a) =>
            a.titre.toLowerCase().contains(query) ||
            a.resume.toLowerCase().contains(query),
      );
    }

    final sorted = list.toList();

    sorted.sort((a, b) {
      DateTime dateA;
      DateTime dateB;

      try {
        dateA = DateTime.parse(a.date);
        dateB = DateTime.parse(b.date);
      } catch (_) {
        dateA = DateTime.fromMillisecondsSinceEpoch(0);
        dateB = DateTime.fromMillisecondsSinceEpoch(0);
      }

      return _sortDescending ? dateB.compareTo(dateA) : dateA.compareTo(dateB);
    });

    setState(() {
      _filteredArticles = sorted;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('MiniPress'),
        actions: [
          IconButton(
            icon: Icon(
              _sortDescending ? Icons.arrow_downward : Icons.arrow_upward,
            ),
            tooltip: _sortDescending
                ? 'Plus récents d\'abord'
                : 'Plus anciens d\'abord',
            onPressed: () {
              setState(() {
                _sortDescending = !_sortDescending;
                _applyFiltersAndSort();
              });
            },
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
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : ListView(
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
                          'Navigation',
                          style: TextStyle(color: Colors.white70, fontSize: 14),
                        ),
                      ],
                    ),
                  ),

                  // Tous les articles
                  ListTile(
                    leading: const Icon(Icons.article_outlined),
                    title: const Text('Tous les articles'),
                    selected:
                        _selectedCategory == null && _selectedAuthor == null,
                    onTap: () {
                      Navigator.of(context).pop();
                      _loadData();
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

                  // Catégories
                  ..._categories.map((cat) {
                    return ListTile(
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
                        _loadByCategory(cat.id);
                      },
                    );
                  }),

                  const Divider(),

                  // Auteurs
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
                        _loadByAuteur(_selectedAuthor!);
                      }
                    },
                  ),
                ],
              ),
      ),

      body: Column(
        children: [
          // Chips filtres actifs
          if (_selectedCategory != null || _selectedAuthor != null)
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 0),
              child: Row(
                children: [
                  if (_selectedCategory != null)
                    Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: InputChip(
                        label: Text(
                          'Catégorie : ${_categories.firstWhere((c) => c.id == _selectedCategory, orElse: () => Categorie(id: -1, titre: '')).titre}',
                        ),
                        onDeleted: () {
                          _loadData();
                        },
                      ),
                    ),
                  if (_selectedAuthor != null)
                    Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: InputChip(
                        label: Text(
                          'Auteur : ${_selectedAuthorName ?? "ID $_selectedAuthor"}',
                        ),
                        onDeleted: () {
                          _loadData();
                        },
                      ),
                    ),
                ],
              ),
            ),

          // Barre de recherche
          Padding(
            padding: const EdgeInsets.all(12),
            child: TextField(
              controller: _searchController,
              onChanged: (value) {
                setState(() {
                  _searchQuery = value;
                  _applyFiltersAndSort();
                });
              },
              decoration: InputDecoration(
                hintText: 'Rechercher par titre ou résumé...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchQuery.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          setState(() {
                            _searchQuery = '';
                            _applyFiltersAndSort();
                          });
                        },
                      )
                    : null,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
          ),

          // Liste
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _errorMessage.isNotEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(
                          Icons.wifi_off,
                          size: 48,
                          color: Colors.grey,
                        ),
                        const SizedBox(height: 16),
                        const Text(
                          'Impossible de contacter le serveur.',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 32),
                          child: Text(
                            _errorMessage,
                            style: const TextStyle(
                              color: Colors.grey,
                              fontSize: 12,
                            ),
                            textAlign: TextAlign.center,
                          ),
                        ),
                        const SizedBox(height: 10),
                        ElevatedButton(
                          onPressed: _loadData,
                          child: const Text('Réessayer'),
                        ),
                      ],
                    ),
                  )
                : _filteredArticles.isEmpty
                ? const Center(child: Text('Aucun article trouvé.'))
                : RefreshIndicator(
                    onRefresh: _loadData,
                    child: ListView.builder(
                      physics: const AlwaysScrollableScrollPhysics(),
                      itemCount: _filteredArticles.length,
                      itemBuilder: (context, index) {
                        final article = _filteredArticles[index];
                        return ArticleTile(
                          article: article,
                          onTap: () => context.go('/articles/${article.id}'),
                          onAuthorTap: () {
                            setState(() {
                              _selectedAuthor = article.idUtilisateur;
                              _selectedAuthorName = article.utilisateur?.pseudo;
                              _selectedCategory = null;
                            });
                            _loadByAuteur(article.idUtilisateur);
                          },
                        );
                      },
                    ),
                  ),
          ),
        ],
      ),
    );
  }
}
