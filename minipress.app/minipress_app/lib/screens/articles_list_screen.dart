import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:minipress_app/main.dart';
import '../modeles/article.dart';
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

  List<Article> _allArticles = [];
  List<Article> _filteredArticles = [];
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
    });
    try {
      // Fetch API
      final results = await Future.wait([
        articleService.getArticles(),
        categorieService.getCategories(),
      ]);
      _allArticles = results[0] as List<Article>;
      _categories = results[1] as List<Categorie>;
      _applyFiltersAndSort();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  void _applyFiltersAndSort() {
    Iterable<Article> list = _allArticles;

    // Filtre Categ
    if (_selectedCategory != null) {
      list = list.where((a) => a.idCategorie == _selectedCategory);
    }
    // Filtre Auteur
    if (_selectedAuthor != null) {
      list = list.where((a) => a.idUtilisateur == _selectedAuthor);
    }
    // Recherche Titre/Resumé
    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      list = list.where(
        (a) =>
            a.titre.toLowerCase().contains(query) ||
            a.resume.toLowerCase().contains(query),
      );
    }

    // Tri Date
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
      // AppBar barre supérieure contenant le titre et les actions
      appBar: AppBar(
        title: const Text('MiniPress'),
        actions: [
          // Row pour une seule ligne
          Row(
            children: [
              // Bouton sens de tri
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
              // Bouton changer le thème
              IconButton(
                icon: const Icon(Icons.brightness_6),
                tooltip: 'Changer le thème',
                onPressed: () {
                  final isCurrentlyDark =
                      Theme.of(context).brightness == Brightness.dark;
                  themeNotifier.value = isCurrentlyDark
                      ? ThemeMode.light
                      : ThemeMode.dark;
                },
              ),
            ],
          ),
        ],
      ),
      // Menu latéral
      drawer: Drawer(
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : ListView(
                padding: EdgeInsets.zero,
                children: [
                  // En-tête du menu latéral
                  const DrawerHeader(
                    decoration: BoxDecoration(
                      color: Color(0xFF5B4CC4),
                    ), // primary theme color (violet)
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
                          'Catégories d\'articles',
                          style: TextStyle(color: Colors.white70, fontSize: 14),
                        ),
                      ],
                    ),
                  ),
                  // Bouton Auteurs — push et attend le résultat
                  ListTile(
                    leading: const Icon(Icons.people_outline),
                    title: const Text('Auteurs'),
                    onTap: () async {
                      Navigator.of(context).pop(); // ferme le drawer
                      final result = await context.push<Map<String, dynamic>>(
                        '/auteurs',
                      );
                      if (result != null) {
                        setState(() {
                          _selectedAuthor = result['id'] as int;
                          _selectedAuthorName = result['nom'] as String;
                          _selectedCategory = null;
                          _applyFiltersAndSort();
                        });
                      }
                    },
                  ),
                  // Btn toutes les catégories
                  ListTile(
                    leading: const Icon(Icons.all_inclusive),
                    title: const Text('Toutes les catégories'),
                    selected: _selectedCategory == null,
                    onTap: () {
                      setState(() {
                        _selectedCategory = null;
                        _selectedAuthor = null;
                        _selectedAuthorName = null;
                        _applyFiltersAndSort();
                      });
                      Navigator.of(context).pop();
                    },
                  ),
                  // Ligne de séparation
                  const Divider(),
                  ..._categories.map((cat) {
                    // Btn des catégories (ça créer tout les boutons avec map au dessus)
                    return ListTile(
                      leading: const Icon(Icons.label_outline),
                      title: Text(cat.titre),
                      selected: _selectedCategory == cat.id,
                      onTap: () {
                        setState(() {
                          _selectedCategory = cat.id;
                          _selectedAuthor = null;
                          _selectedAuthorName = null;
                          _applyFiltersAndSort();
                        });
                        Navigator.of(context).pop();
                      },
                    );
                  }),
                ],
              ),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            // Barre de recherche
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
                contentPadding: const EdgeInsets.symmetric(vertical: 0),
              ),
            ),
          ),
          // Chips filtres actifs
          if (_selectedCategory != null || _selectedAuthor != null)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                children: [
                  // Chips catégorie
                  if (_selectedCategory != null && _categories.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: InputChip(
                        label: Text(
                          'Catégorie : ${_categories.firstWhere((c) => c.id == _selectedCategory, orElse: () => Categorie(id: -1, titre: '')).titre}',
                        ),
                        onDeleted: () {
                          setState(() {
                            _selectedCategory = null;
                            _applyFiltersAndSort();
                          });
                        },
                      ),
                    ),
                  // Chip auteur
                  if (_selectedAuthor != null)
                    Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: InputChip(
                        label: Text(
                          'Auteur : ${_selectedAuthorName ?? "ID $_selectedAuthor"}',
                        ),
                        onDeleted: () {
                          setState(() {
                            _selectedAuthor = null;
                            _selectedAuthorName = null;
                            _applyFiltersAndSort();
                          });
                        },
                      ),
                    ),
                ],
              ),
            ),
          // Liste principale qui prend tout le reste de l'espace
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _errorMessage.isNotEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text('Erreur de chargement : $_errorMessage'),
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
                    // Pour tirer vers le bas pour raffraichir la liste
                    onRefresh: _loadData,
                    // Liste d'article
                    child: ListView.builder(
                      cacheExtent: 30,
                      physics: const AlwaysScrollableScrollPhysics(),
                      itemCount: _filteredArticles.length,
                      itemBuilder: (context, index) {
                        final article = _filteredArticles[index];

                        // Alternance pastel pour correspondre au css de Minipress.core
                        Color? cardBgColor;
                        if (Theme.of(context).brightness == Brightness.light) {
                          if (index % 3 == 1) {
                            cardBgColor = const Color(0xFFEEE9FC);
                          } else if (index % 3 == 2) {
                            cardBgColor = const Color(0xFFFCECEF);
                          }
                        }

                        // Widget ArticleTile
                        return ArticleTile(
                          article: article,
                          backgroundColor: cardBgColor,
                          onTap: () {
                            context.go('/articles/${article.id}');
                          },
                          onAuthorTap: () {
                            setState(() {
                              _selectedAuthor = article.idUtilisateur;
                              _selectedAuthorName = article.utilisateur?.pseudo;
                              _selectedCategory = null;
                              _applyFiltersAndSort();
                            });
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
