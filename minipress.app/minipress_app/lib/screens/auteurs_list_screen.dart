import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart'; // Ajout de Riverpod
import 'package:go_router/go_router.dart';
import '../providers/utilisateur_provider.dart';

class AuteursListScreen extends ConsumerWidget {
  const AuteursListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // On écoute la liste des auteurs fournie par le provider
    final auteursAsync = ref.watch(utilisateurProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Auteurs')),
      body: auteursAsync.when(
        // 1. État de chargement automatique
        loading: () => const Center(child: CircularProgressIndicator()),

        // 2. Gestion des erreurs centralisée
        error: (error, stackTrace) => Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text('Erreur : $error'),
              const SizedBox(height: 10),
              ElevatedButton(
                onPressed: () => ref.refresh(utilisateurProvider),
                child: const Text('Réessayer'),
              ),
            ],
          ),
        ),

        // 3. Affichage des données reçues
        data: (auteurs) {
          if (auteurs.isEmpty) {
            return const Center(child: Text('Aucun auteur trouvé.'));
          }

          return RefreshIndicator(
            onRefresh: () async {
              // Rafraîchit le cache du provider
              ref.refresh(utilisateurProvider);
            },
            child: ListView.builder(
              physics: const AlwaysScrollableScrollPhysics(),
              itemCount: auteurs.length,
              itemBuilder: (context, index) {
                final auteur = auteurs[index];
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
                    // On conserve parfaitement le renvoi de données vers GoRouter
                    context.pop({'id': auteur.id, 'nom': auteur.pseudo});
                  },
                );
              },
            ),
          );
        },
      ),
    );
  }
}
