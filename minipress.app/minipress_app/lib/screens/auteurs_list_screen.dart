import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../modeles/utilisateur.dart';
import '../service/service_api.dart';

class AuteursListScreen extends StatefulWidget {
  const AuteursListScreen({super.key});

  @override
  State<AuteursListScreen> createState() => _AuteursListScreenState();
}

class _AuteursListScreenState extends State<AuteursListScreen> {
  List<Utilisateur> _auteurs = [];
  bool _isLoading = true;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
    _loadAuteurs();
  }

  Future<void> _loadAuteurs() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });
    try {
      _auteurs = await utilisateurService.getAuteurs();
      setState(() => _isLoading = false);
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Auteurs')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage.isNotEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Erreur : $_errorMessage'),
                  const SizedBox(height: 10),
                  ElevatedButton(
                    onPressed: _loadAuteurs,
                    child: const Text('Réessayer'),
                  ),
                ],
              ),
            )
          : RefreshIndicator(
              onRefresh: _loadAuteurs,
              child: ListView.builder(
                physics: const AlwaysScrollableScrollPhysics(),
                itemCount: _auteurs.length,
                itemBuilder: (context, index) {
                  final auteur = _auteurs[index];
                  return ListTile(
                    leading: CircleAvatar(
                      backgroundImage: NetworkImage(
                        'http://docketu.iutnc.univ-lorraine.fr:29029${auteur.cheminAccesImg}',
                      ),
                      backgroundColor: Theme.of(context).colorScheme.primary,
                      onBackgroundImageError: (_, __) {},
                    ),
                    title: Text(auteur.pseudo),
                    trailing: const Icon(Icons.arrow_forward_ios, size: 16),
                    onTap: () {
                      context.pop({'id': auteur.id, 'nom': auteur.pseudo});
                    },
                  );
                },
              ),
            ),
    );
  }
}
