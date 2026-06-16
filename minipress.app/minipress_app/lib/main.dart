import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'router.dart';

final themeNotifier = ValueNotifier<ThemeMode>(ThemeMode.system);
void main() {
  runApp(const ProviderScope(child: MyApp()));
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    const Color violet = Color(0xFF5B4CC4);
    const Color violetFonce = Color(0xFF4335A8);
    const Color violetSombre = Color(0xFF2F276F);
    const Color backgroundClair = Color(0xFFF6F3FD);
    const Color texteClair = Color(0xFF090810);
    const Color texteDouxClair = Color(0xFF5F5A78);

    return ValueListenableBuilder<ThemeMode>(
      valueListenable: themeNotifier,
      builder: (context, currentThemeMode, child) {
        return MaterialApp.router(
          title: 'Minipress',
          debugShowCheckedModeBanner: false,

          // Thème Clair
          theme: ThemeData(
            useMaterial3: true,
            colorScheme: ColorScheme.fromSeed(
              seedColor: violet,
              primary: violet,
              secondary: violetFonce,
              tertiary: violetSombre,
              surface: Colors.white,
              onPrimary: Colors.white,
              onSurface: texteClair,
            ),
            scaffoldBackgroundColor: backgroundClair,

            // Couleur et arrondi des AppBar
            appBarTheme: const AppBarTheme(
              backgroundColor: violet,
              foregroundColor: Colors.white,
              elevation: 4,
              shadowColor: Color(0x263F3786),
            ),

            // Couleur et arrondis des cartes blanches
            cardTheme: CardThemeData(
              color: Colors.white,
              elevation: 2,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(24),
              ),
              shadowColor: const Color(0x263F3786),
            ),

            // Couleur et arrondis des champs de saisie
            inputDecorationTheme: InputDecorationTheme(
              filled: true,
              fillColor: Colors.white,
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(10),
                borderSide: const BorderSide(
                  color: Color(0xFFE3DCF9),
                  width: 2,
                ),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(10),
                borderSide: const BorderSide(
                  color: Color(0xFFE3DCF9),
                  width: 2,
                ),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(10),
                borderSide: const BorderSide(color: violet, width: 2),
              ),
            ),

            // Couleur Drawer
            drawerTheme: const DrawerThemeData(backgroundColor: Colors.white),

            // Couleur Textes
            textTheme: const TextTheme(
              titleLarge: TextStyle(
                color: texteClair,
                fontWeight: FontWeight.bold,
              ),
              titleMedium: TextStyle(color: texteClair),
              bodyMedium: TextStyle(color: texteDouxClair),
            ),
          ),

          // Thème Sombre
          darkTheme: ThemeData(
            useMaterial3: true,
            colorScheme: ColorScheme.fromSeed(
              seedColor: violet,
              brightness: Brightness.dark,
              surface: const Color(0xFF1D1B26),
            ),
            appBarTheme: const AppBarTheme(
              backgroundColor: violetSombre,
              foregroundColor: Colors.white,
            ),
            cardTheme: CardThemeData(
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(24),
              ),
            ),
            inputDecorationTheme: InputDecorationTheme(
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(10),
              ),
            ),
          ),

          themeMode: currentThemeMode,
          routerConfig: router,
        );
      },
    );
  }
}
